<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Jobs\UploadImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the request
        $this->validate($request, [
            'image' => ['required', 'mimes:jpg,gif,bmp,png', 'max:2048']
        ]);

        // Get the image
        $image = $request->file('image');
        $image_path = $image->getPathName();

        // Get the original file name and replace any spaces with _
        // Business Cards.png = timestamp()_business_cards.png
        $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));

        // Move the image to the temporary location (tmp)
        $tmp = $image->storeAs('uploads/original', $filename, 'tmp');

        // Create the database record for the design
        $design = Auth::user()->designs()->create([
            'image' => $filename,
            'disk' => config('site.upload_disk')
        ]);

        // Dispatch a job to handle image manipulation
        $this->dispatch(new UploadImage($design));

        return response()->json($design, 200);

    }
}
