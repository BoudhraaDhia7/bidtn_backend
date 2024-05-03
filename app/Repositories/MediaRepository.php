<?php

namespace App\Repositories;

use App\Exceptions\GlobalException;
use App\Models\Media;
use Illuminate\Database\Eloquent\Model;

class MediaRepository
{
    /**
     * Attaches media to a model and saves it in the database.
     *
     * @param Model $model The model instance to attach the media to.
     * @param array $mediaData The data for the new media item.
     * @return Media The newly created media item.
     * @throws \Exception If the media cannot be saved.
     */
    public static function attachMediaToModel(Model $model, array $mediaData): Media
    {
        $mediaData['model_type'] = get_class($model);
        $mediaData['model_id'] = $model->getKey();

        $media = new Media();
        $media->fill($mediaData);

        if (!$media->save()) {
            throw new GlobalException('failed_to_save_media');
        }

        return $media;
    }

    /**
     * Detaches media from the given model and updates the timestamps.
     *
     * @param Model $model The model instance from which media is to be detached.
     * @param mixed $mediaId The ID of the media to be detached.
     * @return void
     */
    public static function detachMediaFromModel(Model $model, $mediaId, $imageToDeatch): void
    {
        $modelType = get_class($model);

        Media::where([
            'model_type' => $modelType,
            'model_id' => $mediaId,
        ])
            ->whereIn('id', $imageToDeatch)
            ->update(['deleted_at' => time()]); 
  
    }
}
