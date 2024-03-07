<?php

namespace App\Repositories;

use App\Models\Media;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Exception;


class MediaRepository
{
    public static function attachMediaToModel($model, array $mediaData)
    {
        $mediaData['model_type'] = $model::class;
        $mediaData['model_id'] = $model->getKey();

        $media = Media::firstOrNew(
            ['model_type' => $mediaData['model_type'], 'model_id' => $mediaData['model_id']]
        );

        $media->fill($mediaData);
        
        if ($media->isDirty()) {
            $media->save();
        }

        return $media;
    }

}
