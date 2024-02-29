<?php

namespace App\Repositories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class MediaRepository
{
    public function attachMediaToModel($model, array $mediaData)
    {
        if (!$model instanceof Model) {
            throw new InvalidArgumentException("The provided model is not a valid Eloquent model instance.");
        }

        $mediaData['model_type'] = get_class($model);
        $mediaData['model_id']  = $model->getKey();

        $media = Media::create($mediaData);

        return $media;
    }
}