<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;

class DocumentController extends Controller
{
    public function get(Request $request)
    {
        $document = Document::get();
        return response()->json(['questions'=>$document], 200);

    }
}
