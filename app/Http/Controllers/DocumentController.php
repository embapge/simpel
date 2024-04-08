<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function stream(Request $request)
    {
        $mime = Storage::mimeType($request->path);
        return response()->file("../storage/app/{$request->path}", ['content-type' => $mime]);
        getDocument($request->path);
    }
}
