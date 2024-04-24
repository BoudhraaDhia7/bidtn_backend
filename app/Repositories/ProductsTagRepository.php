<?php

namespace App\Repositories;

use App\Models\Product;
use App\Helpers\QueryConfig;
use App\Models\ProductsTag;
use Tymon\JWTAuth\Claims\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductsTagRepository
{   
    /**
     * Create Product.
     *
     * @param int $id
     * @param array $data
     * @return Product
     */
    public static function createTag($name , Array $imageArray)
    {   
        if(empty($imageArray)){
            throw new \Exception('Image is required');
        }

        $tags = ProductsTag::create([
            'name' => $name,
        ]);

        if (!$tags) {
            throw new \Exception('User registration failed');
        }

        $response['tags'] = $tags;
        foreach($imageArray as $image){
            $storedPath = $image->store('profile_pictures', 'public');
            $fullUrl = Storage::url($storedPath);

            $mediaData = [
                'file_name' => $tags->id . '_item_picture',
                'file_path' => $fullUrl,
                'file_type' => $image->getClientMimeType(),
            ];

            MediaRepository::attachMediaToModel($tags, $mediaData);
            $response['images'][] = $mediaData;
        }
        return $response;
    }

    /**
     * Update a Product.
     *
     * @param int $id
     * @param array $data
     * @return ProductsTag
     */
    public static function getAll(QueryConfig $queryConfig): LengthAwarePaginator|Collection
    {
        $productTagsQuery = ProductsTag::with('media');
    
        ProductsTag::applyFilters($queryConfig->getFilters(), $productTagsQuery);
        $productTagsQuery->orderBy($queryConfig->getOrderBy(), $queryConfig->getDirection());
    
        if ($queryConfig->isPaginated()) {
            return $productTagsQuery->paginate($queryConfig->getPerPage());
        }
        return $productTagsQuery->get();
    }

}
