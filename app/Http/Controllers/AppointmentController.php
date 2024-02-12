<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Appointment;
use App\Models\Queue;

use App\Events\QueueEvent;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\DataPart;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
class AppointmentController extends Controller
{
    public function add(Request $request)
    {
        $validate = Validator::make($request->all(), 
        [
            'date' => 'required',
            'email' => 'required|email',
            'values' => 'required',
        ]);

        $appointment = new Appointment;
        $appointment->date = $request->date;
        $appointment->email = $request->email;
        $appointment->values = $request->values;
        $appointment->status_id = 1;
        $appointment->save();

        $appointmentCode = str_pad($appointment->id, 6, '0', STR_PAD_LEFT);

        // Update the appointment with the generated code
        $appointment->code = $appointmentCode;
        $appointment->save();

        $renderer = new ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(400),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
    
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString($appointment->code);
    
        Mail::send([], [], function ($message) use ($qrCode,$request) {
            $message->to($request->email)
                ->subject('QR Code')
                ->attachData($qrCode, 'qrcode.svg');
        });

        return response()->json([
            'status' => true,
            'appointment' => $appointment,
        ], 200);
    }


    public function checkTimeframe(Request $request)
    {
        $request->validate([
            'datetime' => 'required|date_format:Y-m-d H:i:s', // Assuming datetime is in format YYYY-MM-DD HH:MM:SS
        ]);

        $datetime = $request->input('datetime');

        // Parse the input datetime
        $hourlySlot = date('Y-m-d H:00:00', strtotime($datetime));

        // Count the number of appointments for the given hour
        $appointmentsCount = Appointment::whereBetween('date', [
            $hourlySlot,
            date('Y-m-d H:59:59', strtotime($hourlySlot))
        ])->count();

        // Check availability based on the rule (max 10 appointments per hour)
        $isAvailable = $appointmentsCount < 10;

        return response()->json([
            'available_slots' => 10 - $appointmentsCount,
            'is_available' => $isAvailable
        ]);
    }

    public function addQueue(Request $request)
    {
        $request->validate([
            'code' => 'required|string', // Assuming datetime is in format YYYY-MM-DD HH:MM:SS
        ]);

        $appointment = Appointment::where('code',$request->code)->first();

        $event = Queue::where('appointment_id',$appointment->id)->exists();

        if($event == 1){
            return response()->json([
                'status' => 'Already in queue',
            ]);
        }
        
        $queue = new Queue;
        $queue->appointment_id = $appointment->id;
        $queue->save();

        $appointment->status_id = 2;
        $appointment->save();

        $queueList = Queue::join('appointments', 'queues.appointment_id', '=', 'appointments.id')
            ->where('appointments.status_id',2)
            ->orWhere('appointments.status_id',3)
            ->orderBy('queues.id', 'asc')
            ->get()->map(function ($item) {
                return $item->toArray();
            });
            
        //return $queueList;

        event(new QueueEvent($queueList));

        return response()->json([
            'status' => 'Success',
        ]);
    }

    public function queueList(Request $request)
    {
      
        $queueList = Queue::join('appointments', 'queues.appointment_id', '=', 'appointments.id')
            ->where('appointments.status_id',2)
            ->orWhere('appointments.status_id',3)->get();
            
        return response()->json([
            'appointment' => $queueList,
        ]);
    }

    public function process(Request $request)
    {
        $queue = Queue::join('appointments', 'queues.appointment_id', '=', 'appointments.id')
            ->where('appointments.status_id',2)->orderBy('queues.id','asc')->first();

        $appointment = Appointment::find($queue->appointment_id);
        $appointment->status_id = 3;
        $appointment->save();
        
        $queueList = Queue::join('appointments', 'queues.appointment_id', '=', 'appointments.id')
        ->where('appointments.status_id',2)
        ->orWhere('appointments.status_id',3)
        ->orderBy('queues.id', 'asc')
        ->get()->map(function ($item) {
            return $item->toArray();
        });

        event(new QueueEvent($queueList));

        return response()->json([
            'status' => 'Success',
        ]);
    }

    public function appointmentList(Request $request)
    {
        if(!empty($request->code))
        {
        $appointment = Appointment::where('code',$request->code)->paginate($request->limit);
        return $appointment;
        }

        $appointment = Appointment::paginate($request->limit);
        
        return $appointment;
    }

    public function editAppointment(Request $request)
    {
        $validate = Validator::make($request->all(), 
        [
            'date' => 'required',
            'email' => 'required|email',
            'values' => 'required',
            'appointment_id' => 'required',
        ]);

        $appointment = Appointment::find($request->appointment_id);
        $appointment->date = $request->date;
        $appointment->email = $request->email;
        $appointment->values = $request->values;
        $appointment->save();

        return response()->json([
            'status' => 'Success',
        ]);
    }

    public function generateClearanceDocument($name, $age)
    {
            // Load the Word template file
            $templateFile = public_path('templates/BRGY-CLEARANCE-2019.docx');
            $templateProcessor = new TemplateProcessor($templateFile);

            // Replace placeholders with dynamic data
            $templateProcessor->setValue('name', $name);
            $templateProcessor->setValue('age', $age);
            $templateProcessor->setValue('date', '2020-12-12');


            // Save the modified document
            $outputFile = public_path('generated/barangay_clearance_'.$name.'.docx');
            $templateProcessor->saveAs($outputFile);

            // Download the generated Word file
            return response()->download($outputFile);

    }

    public function generateClearance(Request $request)
    {
        $name = $request->name;
        $age = $request->age;

        return $this->generateClearanceDocument($name, $age);
    }
}
