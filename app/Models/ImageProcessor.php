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
        // Check image size
        $size = filesize($imgFile->getRealPath());
        
        $photo = new Request();
        $imgExt = $imgFile->getClientOriginalExtension();
        $filename = $name.'_'.DateTime::Now().'_'.$index.'.'.$imgExt;
        $imageThumb = null;
        // Check if size of image exceed the blob limit
        if ($size > 65535) {
            $imageThumb = ImageProcessor::compress($imgFile, 480, 480);
        }
        else {
            $imageThumb = Image::make($imgFile->getRealPath());
        }
        Response::make($imageThumb->encode($imgExt));

        $photo->merge([
            $filenameFieldname => $filename,
            $photoFieldname => $imageThumb
        ]);

        return $photo;
    }

    
    private static function compress($image, $width, $height) {
        return Image::make($image->getRealPath())->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
}
