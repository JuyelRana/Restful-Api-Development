<?php

namespace App\Jobs;

use App\Models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $design;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Design $design)
    {
        $this->design = $design;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $disk = $this->design->disk;
        $filename = $this->design->image;
        $original_file = storage_path() . '/uploads/original/' . $filename;

        try {
            // Create the large image and save to tmp disk
            Image::make($original_file)
                ->fit(800, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($large_file = storage_path('uploads/large/' . $filename));

            // Create the thumbnail image and save to tmp disk
            Image::make($original_file)
                ->fit(250, 200, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumbnail_file = storage_path('uploads/thumbnail/' . $filename));

            // Store Images to permanent disk
            // Original Image
            if (Storage::disk($disk)
                ->put('uploads/designs/original/' . $filename, fopen($original_file, 'r+'))) {
                File::delete($original_file);
            }

            // Large Image
            if (Storage::disk($disk)
                ->put('uploads/designs/large/' . $filename, fopen($large_file, 'r+'))) {
                File::delete($large_file);
            }

            // Thumbnail Image
            if (Storage::disk($disk)
                ->put('uploads/designs/thumbnail/' . $filename, fopen($thumbnail_file, 'r+'))) {
                File::delete($thumbnail_file);
            }

            // Update the database record with success flag
            $this->design->update([
                'upload_successful'=>true
            ]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
