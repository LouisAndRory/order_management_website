<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('file');

        $path = now()->toDateString();
        $fileName = now()->timestamp;

        \Storage::putFileAs($path, $file, $fileName);

        $url = \Storage::url($path . '/' . $fileName);

        return response()->json([
            'message' => 'OK',
            'data' => [
                'url' => $url
            ]
        ]);
    }
}