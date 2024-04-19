<?php

namespace App\Repositories;

use App\Models\Product;
use App\Helpers\QueryConfig;
use Tymon\JWTAuth\Claims\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{   
    /**
     * Create item.
     *
     * @param int $id
     * @param array $data
     * @return Product
     */
    public static function createProduct($name, $category , $description , Array $imageArray)
    {   
        $user = auth()->user();
        if(!$user){
            throw new \Exception('User not found');
        }
        if(empty($imageArray)){
            throw new \Exception('Image is required');
        }

        $product = Product::create([
            'name' => $name,
            'category' => $category,
            'description' => $description,
            'images' => $imageArray,
            'user_id' => $user->id,
        ]);

        if (!$product) {
            throw new \Exception('User registration failed');
        }

        $response['product'] = $product;

        foreach($imageArray as $image){
            $storedPath = $image->store('profile_pictures', 'public');
            $fullUrl = Storage::url($storedPath);

            $mediaData = [
                'file_name' => $product->id . '_item_picture',
                'file_path' => $fullUrl,
                'file_type' => $image->getClientMimeType(),
            ];

            MediaRepository::attachMediaToModel($product, $mediaData);
            $response['images'][] = $mediaData;
        }
        return $response;
    }

 
    
    public static function GetProducts(QueryConfig $queryConfig, $userId): LengthAwarePaginator|Collection
    {   
        if(!$userId){
            throw new \Exception('User not found');
        }
    
        $productQuery = Product::with(['media', 'categories.media']);
    
        Product::applyFilters($queryConfig->getFilters(), $productQuery);
        //$productQuery->where('user_id', $userId)->orderBy($queryConfig->getOrderBy(), $queryConfig->getDirection());
        $productQuery->orderBy($queryConfig->getOrderBy(), $queryConfig->getDirection());
    
        if ($queryConfig->isPaginated()) {
            return $productQuery->paginate($queryConfig->getPerPage());
        }
        return $productQuery->get();
    }
    

 //TODO - Implement the following methods
 
    // public function delete($id)
    // {
    //     $product = Item::find($id);
    //     if ($item) {
    //         return $item->delete();
    //     }
    //     return null;
    // }

    //    /**
    //  * Update a item.
    //  *
    //  * @param int $id
    //  * @param array $data
    //  * @return Item
    //  */
    // public static function updateItem($id, $name, $category, $description, array $newImageArray)
    // {
    //     $item = Item::find($id);

    //     if (!$item) {
    //         throw new \Exception('Item not found');
    //     }
    //     if ($item->user_id !== auth()->user()->id) {
    //         throw new \Exception('User is not authorized to update this item');
    //     }

    //     $item->update([
    //         'name' => $name,
    //         'category' => $category,
    //         'description' => $description,
    //     ]);

    //     $response['item'] = $item;

    //     if (!empty($newImageArray)) {
    //         foreach ($newImageArray as $image) {
    //             $storedPath = $image->store('item_images', 'public');
    //             $fullUrl = Storage::url($storedPath);

    //             $mediaData = [
    //                 'file_name' => $item->id . '_item_picture',
    //                 'file_path' => $fullUrl,
    //                 'file_type' => $image->getClientMimeType(),
    //             ];

    //             MediaRepository::attachMediaToModel($item, $mediaData);
    //             $response['images'][] = $mediaData;
    //         }
    //     } else {
    //         throw new \Exception('At least one image is required');
    //     }

    //     return $response;
    // }

    //  /**
    //  * Find item by id.
    //  *
    //  * @param int $id
    //  * @return Item
    //  */
    // public static function findById($id)
    // {
    //     $item = Item::find($id);

    //     if (!$item) {
    //         throw new \Exception('Item not found');
    //     }

    //     if ($item->user_id !== auth()->user()->id) {
    //         throw new \Exception('User is not authorized to view this item');
    //     }

    //     return $item;
    // }
}
