<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

trait ImageFile
{
    /**
     * Upload image to storage
     *
     * @param  \Illuminate\Http\UploadedFile  $image
     * @return string
     */
    public function uploadImage($image, $path = '')
    {
        if (empty($image) || is_null($image)) {
            return false;
        }

        $filename =  date('Y-m-d-') . uniqid() . '.' . $image->getClientOriginalExtension();

        $image = Image::make($image)->resize(null, 1024, function ($constraint) {
            $constraint->aspectRatio();
        })->stream()->detach();

        $store = Storage::disk('public')->put(
            $path . '/' . $filename,
            $image
        );

        if (!$store) {
            return false;
        }

        return $path .'/'.  $filename;
    }

    public function deleteImage($image)
    {
        if (empty($image)) {
            return false;
        }

        $deleteImage = Storage::disk('public')->delete($image);

        if ((!$deleteImage)) {
            return false;
        }

        return true;
    }
}

