<?php

namespace App\Repositories;

use App\Exceptions\GlobalException;
use App\Helpers\MediaHelpers;
use App\Models\Auction;
use App\Helpers\QueryConfig;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AuctionRepository
{
    /**
     * Get all auctions from the database.
     *
     * @param QueryConfig $queryConfig
     * @return LengthAwarePaginator|Collection
     */
    public static function index(QueryConfig $queryConfig, $user): LengthAwarePaginator|Collection
    {   
        $auctionQuery = Auction::with(['product.media', 'product.categories']);
        
        Auction::applyFilters($queryConfig->getFilters(), $auctionQuery);

        
        if (!$user->isAdmin) {
            $auctionQuery->where('user_id', $user->id);
        }
        
        $auctionQuery->orderBy($queryConfig->getOrderBy(), $queryConfig->getDirection());
        
        if ($queryConfig->isPaginated()) {
            return $auctionQuery->paginate($queryConfig->getPerPage());
        }
        
        return $auctionQuery->get();
    }

    /**
     * update a auction record in the database.
     *
     * @param array $validated Validated data from the request
     * @return array
     * @throws Exception if the auction creation fails
     */

    //public static function updateProduct($id, $name, $category, $description, array $newImageArray, array $deletedImages, $user)
    public static function updateAuction($title, $description, $startingPrice, $startDate, $startingUserNumber, $products, $auction , $user)
    {   
        DB::beginTransaction();

        $auction->update([
            'title' => $title,
            'description' => $description,
            'startingPrice' => $startingPrice,
            'startDate' => $startDate,
            'startingUserNumber' => $startingUserNumber,
        ]);


        foreach($products as $product){
            $id = $product['id'];
            $name = $product['name'];
            $categories = $product['categories'];
            $description = $product['description'];
            $newImageArray = isset($product['files']) ? $product['files'] : [];
            $deletedImages = isset($product['deletedMedia']) ? $product['deletedMedia'] : [];
             $id != 0 ? ProductRepository::updateProduct($id, $name, $categories, $description, $newImageArray, $deletedImages, $user):
             ProductRepository::storeProduct($name, $description ,$categories, $newImageArray, $user, $auction);
        }
        DB::commit();
        return $auction;

    }

    /**
     * Create a new auction record in the database.
     *
     * @param array $validated Validated data from the request
     * @return array
     * @throws Exception if the auction creation fails
     */
    public static function createAuction($title, $description, $startingPrice, $startDate, $startingUserNumber, $products, $user): array
    {
        try {
            DB::beginTransaction();

            $auction = Auction::create([
                'title' => $title,
                'description' => $description,
                'starting_price' => $startingPrice,
                'start_date' => $startDate,
                'starting_user_number' => $startingUserNumber,
                'user_id' => $user->id,
            ]);

            if (!$auction) {
                DB::rollback();
                throw new GlobalException('Auction creation failed', 400);
            }

            self::processProducts($products, $user, $auction);
            DB::commit();
            return $auction->toArray();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Process the products array and create the products in the database.
     * @param array $products
     * @param $user
     * @param $auction
     * @throws GlobalException
     */
    private static function processProducts($products, $user, $auction)
    {
        foreach ($products as $productData) {
            $productData['auction_id'] = $auction->id;
            $productData['user_id'] = $user->id;

            $product = Product::create($productData);
            if (!$product) {
                throw new GlobalException('Product creation failed', 404);
            }

            self::attachMediaAndCategories($product, $productData);
        }
    }

    /**
     * Attach media and categories to the product.
     * @param $product
     * @param $productData
     */
    private static function attachMediaAndCategories($product, $productData)
    {
        foreach ($productData['files'] as $file) {
            $mediaData = MediaHelpers::storeMedia($file, 'product_images', $product);
            MediaRepository::attachMediaToModel($product, $mediaData);
        }
        $product->categories()->attach($productData['categories']);
    }

    /**
     * Delete a auction record from the database.
     *
     * @param int $id
     * @param $user
     * @return Auction
     */
    public static function deleteAuction($auction)
    {
        $auction->delete();

        return $auction;
    }
}
