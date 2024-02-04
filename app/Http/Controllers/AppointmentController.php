<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Appointment;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\DataPart;

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
}
