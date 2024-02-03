<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Timeframe;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\DataPart;

class DocumentController extends Controller
{
    public function get(Request $request)
    {
        $document = Document::get();
        return response()->json(['documents'=>$document], 200);
    }

    public function timeframe(Request $request)
    {
        $timeframe = Timeframe::get();
        return response()->json(['Timeframe'=>$timeframe], 200);
    }

    public function qr(Request $request)
    {
        $renderer = new ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(400),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
    
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString('Your QR Code Data');
    
        
        Mail::send([], [], function ($message) use ($qrCode) {
            $message->to('johncarlocasipit@gmail.com')
                ->subject('QR Code')
                ->attachData($qrCode, 'qrcode.svg');
        });

        return "QR Code sent via email.";

    }






}
