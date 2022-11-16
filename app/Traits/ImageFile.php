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

        if (File::size($image) > 2000000) {
            return \jsonResponse(false, 'Image size is too large (max 2 m)', null, 400);
        }

        $filename =  date('Y-m-d-') . uniqid() . '.' . $image->getClientOriginalExtension();


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

