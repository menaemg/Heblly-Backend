<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

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

        $filename = $path . '/' . date('Y-m-d-') . uniqid() . '.' . $image->getClientOriginalExtension();
        $storeImage = Image::make($image)->save(storage_path("app/public/$filename"));

        if (!$storeImage) {
            return false;
        }

        return $filename;
    }

    public function deleteImage($image)
    {
        if (empty($image)) {
            return false;
        }

        $deleteImage = \file_exists(storage_path("app/public/$image")) ? File::delete(storage_path("app/public/$image")) : false;

        if ((!$deleteImage)) {
            return false;
        }

        return true;
    }
}

