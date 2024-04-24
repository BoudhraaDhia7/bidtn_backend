<?php

namespace App\Repositories;

use App\Exceptions\GlobalException;
use App\Models\Product;
use App\Helpers\QueryConfig;
use App\Helpers\MediaHelpers;
use Tymon\JWTAuth\Claims\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    /**
     * Create Product.
     *
     * @param int $id
     * @param array $data
     * @return Product
     */
    public static function storeProduct($name, $description, array $categoriesArray, array $imageArray, $user) : array
    {
        if (empty($imageArray)) {
            throw new GlobalException('product_image_required' , 400);
        }

        if (empty($categoriesArray)) {
            throw new GlobalException('product_category_required' , 400);
        }

        $product = Product::create([
            'name' => $name,
            'description' => $description,
            'user_id' => $user->id,
        ]);

        $product->categories()->attach($categoriesArray);

        if (!$product) {
            throw new GlobalException('user_registration_failed' , 400);
        }

        $response['product'] = $product;
        foreach ($imageArray as $image) {
            $mediaData = MediaHelpers::storeMedia($image, 'product_images', $product);
            MediaRepository::attachMediaToModel($product, $mediaData);
            $response['images'][] = $mediaData;
        }
        return $response;
    }

    /**
     * Get all products.
     *
     * @param QueryConfig $queryConfig
     * @param $user
     * @return LengthAwarePaginator|Collection
     */
    public static function GetProducts(QueryConfig $queryConfig, $user): LengthAwarePaginator|Collection
    {   
        $productQuery = Product::with(['media', 'categories.media']);

        Product::applyFilters($queryConfig->getFilters(), $productQuery);
        if (!$user->isAdmin) {
            $productQuery->where('user_id', $user->id);
        }
        $productQuery->orderBy($queryConfig->getOrderBy(), $queryConfig->getDirection());

        if ($queryConfig->isPaginated()) {
            return $productQuery->paginate($queryConfig->getPerPage());
        }
        return $productQuery->get();
    }

    /**
     * Get a product.
     *
     * @param int $id
     * @param $user
     * @return Product
     */
    public static function GetProduct($id, $user)
    {
       
        $product = Product::with(['media', 'categories.media'])->find($id);
        if (!$product) {
            throw new GlobalException('product_not_found',404);
        }
        if (!$user->isAdmin && $product->user_id !== $user->id) {
            throw new GlobalException('product_unauthorized_view' ,  401);
        }
        return [$product];
    }

    /**
     * Delete a product.
     *
     * @param int $id
     * @param $user
     * @return bool
     */
    public static function deleteProduct($id, $user)
    {
        $product = Product::find($id);

        if (!$product) {
            throw new GlobalException('product_not_found' , 302);
        }

        if ($product->user_id !== auth()->user()->id && !$user->isAdmin) {
            throw new GlobalException('product_unauthorized' , 401);
        }

        if (!$product->delete()) {
            throw new GlobalException('product_deleted_failed' , 500);
        }

        return true;
    }

    /**
     * Update a product.
     *
     * @param int $id
     * @param array $data
     * @return Product
     */
    public static function updateProduct($id, $name, $category, $description, array $newImageArray, array $deletedImages, $user)
    {   
        $product = Product::find($id);

        if (!$product) {
            throw new GlobalException('product_not_found', 404);
        }

        if ($product->user_id !== auth()->user()->id && !$user->isAdmin) {
            throw new GlobalException('product_unauthorized' , 401);
        }

        $product->update([
            'name' => $name,
            'category' => $category,
            'description' => $description,
        ]);

        MediaRepository::detachMediaFromModel($product, $product->id, $deletedImages);
        foreach ($newImageArray as $image) {
            $mediaData = MediaHelpers::storeMedia($image, 'product_images', $product);
            MediaRepository::attachMediaToModel($product, $mediaData);
        }

    }
}
