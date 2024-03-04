<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Appointment;
use App\Models\Queue;
use DateTime;
use App\Events\QueueEvent;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\DataPart;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
class AppointmentController extends Controller
{
    public function dashboard(Request $request)
    {
        $reserve = Appointment::where('status_id',1)->count();
        $appointment = Appointment::count();
        $archive = Appointment::where('status_id',5)->count();
        $documents = Appointment::select('document_id', \DB::raw('count(*) as appointment_count'))
        ->where('status_id', 4)
        ->groupBy('document_id')
        ->orderByDesc('appointment_count')
        ->limit(3) // Limit to the top 3 documents with the highest count
        ->get();

        return response()->json([
            'pending' => $reserve,
            'appointment' => $appointment,
            'archive' => $archive,
            'documents' => $documents

        ], 200);
    }
    public function add(Request $request)
    {
        $validate = Validator::make($request->all(), 
        [
            'date' => 'required',
            'email' => 'required|email',
            'values' => 'required',
            'document_id' => 'required',

        ]);

        $appointment = new Appointment;
        $appointment->date = $request->date;
        $appointment->email = $request->email;
        $appointment->values = $request->values;
        $appointment->document_id = $request->document_id;
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
        
        if(empty($appointment))
        {
            return response()->json([
                'status' => 'No Appointment in queue',
            ]);
        }

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
        $current = Appointment::where('status_id',3)->first();
        if(!empty($current)){
            $current->status_id = 4;
            $current->save();
        }
        
        $queue = Queue::join('appointments', 'queues.appointment_id', '=', 'appointments.id')
            ->where('appointments.status_id',2)->orderBy('queues.id','asc')->first();
      
        if(empty($queue))
        {
            return response()->json([
                'status' => 'No appointment in queue',
            ]);
        }

        

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
            'appointment' => $queueList,
        ]);
    }
    public function download(Request $request)
    {
        $request->validate([
            'code' => 'required|string', // Assuming datetime is in format YYYY-MM-DD HH:MM:SS
        ]);

        $appointment = Appointment::where('code',$request->code)->first();
        $values = json_decode($appointment->values);
        if($appointment->document_id == 1)
        {
            $data = $this->generateBrgyClearance($values[0]->value,$values[2]->value,$values[1]->value,$appointment->date);
            return $data;
        }else if($appointment->document_id == 2)
        {
            $data = $this->generateBusinessClearance($values[0]->value,$values[1]->value,$values[2]->value,$values[3]->value,$appointment->date);
            return $data;
        }else if($appointment->document_id == 3)
        {
            $data = $this->generateIndigency($values[0]->value,$values[2]->value,$values[1]->value,$appointment->date);
            return $data;
        } else if($appointment->document_id == 4)
        {
            $data = $this->generateResidency($values[0]->value,$values[1]->value,$appointment->date);
            return $data;
        }
        else if($appointment->document_id == 5)
        {
            $data = $this->generateTricycle($values[0]->value,$values[1]->value,$values[2]->value,$values[3]->value,$values[4]->value,$values[5]->value,$values[6]->value,$appointment->date);
            return $data;
        }

       
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

    public function deleteAppointment(Request $request)
    {
        $validate = Validator::make($request->all(), 
        [
            'appointment_id' => 'required',
        ]);

        $appointment = Appointment::find($request->appointment_id);
        $appointment->status_id = 5;
        $appointment->save();

        return response()->json([
            'status' => 'Success',
        ]);
    }

    public function generateBrgyClearance($name, $age, $purpose,$date)
    {
            // Load the Word template file
            $templateFile = public_path('templates/BRGY-CLEARANCE-2019.docx');
            $templateProcessor = new TemplateProcessor($templateFile);

            // Replace placeholders with dynamic data
            $templateProcessor->setValue('name', $name);
            $templateProcessor->setValue('age', $age);
            $templateProcessor->setValue('purpose', $purpose);
            $templateProcessor->setValue('date', $date);

            // Save the modified document
            $name = str_replace(' ', '', $name);
            $outputFile = storage_path('app/public/generated/barangay_clearance_'.$name.'.docx');
            $templateProcessor->saveAs($outputFile);

            // Get the URL for the stored file
            return response()->download($outputFile)->deleteFileAfterSend(true);
    }

    public function generateIndigency($name, $age, $purpose,$dates)
    {
            // Load the Word template file
            $templateFile = public_path('templates/indigency-2022-NEW.docx');
            $templateProcessor = new TemplateProcessor($templateFile);

            // Replace placeholders with dynamic data
            $templateProcessor->setValue('name', $name);
            $templateProcessor->setValue('age', $age);
            $templateProcessor->setValue('purpose', $purpose);
            $date = new DateTime($dates);
            // Extract day and month
                $day = $date->format('d'); // Format 'd' returns day with leading zeros (01-31)
                $month = $date->format('F');
    
                $templateProcessor->setValue('day', $day);
                $templateProcessor->setValue('month', $month);

            // Save the modified document
            $name = str_replace(' ', '', $name);
            $outputFile = storage_path('app/public/generated/indigency-2022-NEW'.$name.'.docx');
            $templateProcessor->saveAs($outputFile);

            // Get the URL for the stored file
            return response()->download($outputFile)->deleteFileAfterSend(true);
    }

    public function generateResidency($name, $purpose,$dates)
    {
            // Load the Word template file
            $templateFile = public_path('templates/RESIDENCY-2023.docx');
            $templateProcessor = new TemplateProcessor($templateFile);

            // Replace placeholders with dynamic data
            $templateProcessor->setValue('name', $name);
            $templateProcessor->setValue('purpose', $purpose);
            $date = new DateTime($dates);
            // Extract day and month
                $day = $date->format('d'); // Format 'd' returns day with leading zeros (01-31)
                $month = $date->format('F');
    
                $templateProcessor->setValue('day', $day);
                $templateProcessor->setValue('month', $month);

            // Save the modified document
            $name = str_replace(' ', '', $name);
            $outputFile = storage_path('app/public/generated/residency'.$name.'.docx');
            $templateProcessor->saveAs($outputFile);

            // Get the URL for the stored file
            return response()->download($outputFile)->deleteFileAfterSend(true);
    }

    public function generateTricycle($name, $location,$color,$motor,$plate,$body,$chasis,$dates)
    {
            // Load the Word template file
            $templateFile = public_path('templates/TRICYCLE-2019.docx');
            $templateProcessor = new TemplateProcessor($templateFile);

            // Replace placeholders with dynamic data
            $templateProcessor->setValue('name', $name);
            $templateProcessor->setValue('location', $location);
            $templateProcessor->setValue('color', $color);
            $templateProcessor->setValue('plate', $plate);
            $templateProcessor->setValue('motor', $motor);
            $templateProcessor->setValue('body', $body);
            $templateProcessor->setValue('chasis', $chasis);


            $date = new DateTime($dates);
            // Extract day and month
                $day = $date->format('d'); // Format 'd' returns day with leading zeros (01-31)
                $month = $date->format('F');
    
                $templateProcessor->setValue('day', $day);
                $templateProcessor->setValue('month', $month);

            // Save the modified document
            $name = str_replace(' ', '', $name);
            $outputFile = storage_path('app/public/generated/TRICYCLE-2019'.$name.'.docx');
            $templateProcessor->saveAs($outputFile);

            // Get the URL for the stored file
            return response()->download($outputFile)->deleteFileAfterSend(true);
    }

    public function generateBusinessClearance($name, $trade, $type,$location,$dates)
    {
            // Load the Word template file
            $templateFile = public_path('templates/BUSS2020.docx');
            $templateProcessor = new TemplateProcessor($templateFile);

            // Replace placeholders with dynamic data
            $templateProcessor->setValue('name', $name);
            $templateProcessor->setValue('trade', $trade);
            $templateProcessor->setValue('type', $type);
            $templateProcessor->setValue('location', $location);

            $date = new DateTime($dates);
        // Extract day and month
            $day = $date->format('d'); // Format 'd' returns day with leading zeros (01-31)
            $month = $date->format('F');

            $templateProcessor->setValue('day', $day);
            $templateProcessor->setValue('month', $month);


            // Save the modified document
            $name = str_replace(' ', '', $name);
            $outputFile = storage_path('app/public/generated/buss2020'.$name.'.docx');
            $templateProcessor->saveAs($outputFile);

            // return $day;

            // Get the URL for the stored file
            return response()->download($outputFile)->deleteFileAfterSend(true);
    }
    

    public function generateClearance(Request $request)
    {
        $name = $request->name;
        $age = $request->age;

        return $this->generateClearanceDocument($name, $age);
    }
}
