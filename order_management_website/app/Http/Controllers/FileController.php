<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $type = $request->post('type');

        $path = $type ?: '/';
        $fileName = now()->timestamp . '_' . str_random(15) . '.' . $file->getClientOriginalExtension();

        try {
            \Storage::disk('gcs')->putFileAs($path, $file, $fileName);

            $url = \Storage::disk('gcs')->url($path . '/' . $fileName);

            return response()->json([
                'message' => 'OK',
                'data' => [
                    'url' => $url
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
