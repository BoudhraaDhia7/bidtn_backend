<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class MediaHelpers
{
    /**
     * Store an image and return media data.
     *
     * @param  \Illuminate\Http\UploadedFile  $image
     * @param  \App\Models\Product  $product
     * @return array
     */
    public static function storeMedia($image, $storePath, $instance)
    {
        $storedPath = $image->store($storePath, 'public');
        $fullUrl = Storage::url($storedPath);
        return [
            'file_name' => $instance->id . '_item_picture',
            'file_path' => config('constants.MEDIA_PATH') . $fullUrl,
            'file_type' => $image->getClientMimeType(),
        ];
    }
}
