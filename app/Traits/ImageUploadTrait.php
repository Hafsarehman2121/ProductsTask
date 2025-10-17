<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
trait ImageUploadTrait
{
    /**
     * Upload an image and return its stored path.
     */
    public function uploadImage(UploadedFile $image, $directory = 'uploads')
    {
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();

        $path = $image->storeAs("public/{$directory}", $filename);

        return Storage::url($path);
    }

    /**
     * Delete image if it exists
     */
    public function deleteImage($path)
    {
        if ($path) {
            $storagePath = str_replace('/storage/', 'public/', $path);
            if (Storage::exists($storagePath)) {
                Storage::delete($storagePath);
            }
        }
    }
}
