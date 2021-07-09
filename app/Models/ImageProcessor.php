<?php

namespace App\Models;

use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

/**
 * @author Rizky A
 */
class ImageProcessor
{
    /**
     * Get the thumbnail of the image
     * 
     * @return Illuminate\Http\Request $photo['photo','filename']
     */
    public static function getImageThumbnail($imgFile, $name, $filenameFieldname, $photoFieldname, $index = '')
    {
        $photo = new Request();
        $imgExt = $imgFile->getClientOriginalExtension();
        $filename = $name.'_'.DateTime::Now().'_'.$index.'.'.$imgExt;
        $imageThumb = Image::make($imgFile->getRealPath())->resize(200, 200, function ($constraint) {
            $constraint->aspectRatio();
        });
        Response::make($imageThumb->encode($imgExt));

        $photo->merge([
            $filenameFieldname => $filename,
            $photoFieldname => $imageThumb
        ]);

        return $photo;
    }
}
