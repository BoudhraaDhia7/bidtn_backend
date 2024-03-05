<?php

namespace App\Repositories;

use App\Models\Media;
use InvalidArgumentException;
use Symfony\Component\Finder\Glob;
use App\Exceptions\GlobalException;
use App\Exceptions\AddMediaException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class MediaRepository
{
    public static function attachMediaToModel($model, array $mediaData)
    {
        if (!$model instanceof Model) {
            throw new InvalidArgumentException('The provided model is not a valid Eloquent model instance.');
        }
        try {
            $mediaData['model_type'] = get_class($model);
            $mediaData['model_id'] = $model->getKey();

            $media = Media::create($mediaData);

            return $media;
        } catch (\Exception) {
            throw new GlobalException();
        } catch (QueryException) {
            throw new AddMediaException();
        }
    }
}
