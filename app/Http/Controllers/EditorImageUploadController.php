<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EditorImageUploadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), ['upload' => 'required|file|image|max:5000']);

       if ($validator->fails()) {
           return response()->json([
               'error' => [
                   'message' => $validator->errors()->first('upload')
               ]
           ], 400);
       }

       $path = $request->file('upload')->store('uploads', ['disk' => 'public']);
       $imageURL = Storage::disk('public')->url($path);

       return response()->json(['url' => $imageURL]);
    }
}
