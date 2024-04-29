<?php

namespace App\Repositories;

use App\Exceptions\GlobalException;
use App\Helpers\MediaHelpers;
use App\Models\Auction;
use App\Helpers\QueryConfig;
use App\Models\Product;
use GuzzleHttp\Psr7\Response;
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
    public static function index(QueryConfig $queryConfig): LengthAwarePaginator|Collection
    {
        $auctionQuery = Auction::with(['product.media', 'product.categories']);

        Auction::applyFilters($queryConfig->getFilters(), $auctionQuery);

        if ($queryConfig->getPaginated()) {
            $auctions = $auctionQuery->orderBy($queryConfig->getOrderBy(), $queryConfig->getDirection())->paginate($queryConfig->getPerPage());
        } else {
            $auctions = $auctionQuery->orderBy($queryConfig->getOrderBy(), $queryConfig->getDirection())->get();
        }

        return $auctions;
    }

    /**
     * Create a new auction record in the database.
     *
     * @param array $validated Validated data from the request
     * @return array
     * @throws Exception if the auction creation fails
     */
    public static function createAuction($validated, $products, $user): array
    {
        try {
            DB::beginTransaction();

            $auctionAttributes = self::filterAuctionAttributes($validated);
            $auctionAttributes['user_id'] = $user->id;

            $auction = Auction::create($auctionAttributes);
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

    private static function attachMediaAndCategories($product, $productData)
    {
        foreach ($productData['files'] as $file) {
            $mediaData = MediaHelpers::storeMedia($file, 'product_images', $product);
            MediaRepository::attachMediaToModel($product, $mediaData);
        }
        $product->categories()->attach($productData['categories']);
    }

    private static function filterAuctionAttributes($validated): array
    {
        $attributesToSet = ['title', 'description', 'starting_price', 'start_date', 'end_date', 'starting_user_number', 'is_confirmed', 'is_finished'];
        return array_intersect_key($validated, array_flip($attributesToSet));
    }
}
