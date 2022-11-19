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

        // $image = Image::make($image);

        // $image->resize(800, null, function ($constraint) {
        //     $constraint->aspectRatio();
        // });

        // $resource = $image->stream()->detach();

        // $store = Storage::disk('s3')->put(
        //     $path . '/' . $filename,
        //     $resource
        // );

        $filename = $image->storeAs($path , $filename, 's3');

        if (!$filename) {
            return false;
        }

        return $filename;
    }

    public function deleteImage($image)
    {
        if (empty($image)) {
            return false;
        }

        $deleteImage = Storage::disk('s3')->delete($image);

        if ((!$deleteImage)) {
            return false;
        }

        return true;
    }
}

