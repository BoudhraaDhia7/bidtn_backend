<?php

namespace App\Repositories;

use App\Exceptions\GlobalException;
use App\Models\Auction;
use App\Helpers\QueryConfig;
use App\Models\Product;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
        $auctionQuery = Auction::with(['product.media' , 'product.categories']);

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
    public static function createAuction($validated, $user): array
    {

        $attributesToSet = ['title', 'description', 'starting_price', 'start_date', 'end_date' , 'starting_user_number', 'is_confirmed' , 'is_finished'];

        $filteredAttributes = array_intersect_key($validated, array_flip($attributesToSet));

        $filteredAttributes['user_id'] = $user->id;
        $auction = Auction::create($filteredAttributes);

        //relation with product && check if product exists and belongs to the user
        $product = Product::where('id', $validated['product_id'])->where('user_id', $user->id)->first();
        if(!$product){
            throw new GlobalException('product_not_found' , 404);
        }

        $auction->product()->save($product);

        if (!$auction) {
            throw new GlobalException('auction_creation_failed' , 400);
        }

        return $auction->toArray();
    }
}
