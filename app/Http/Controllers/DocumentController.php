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

}
