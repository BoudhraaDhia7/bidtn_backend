<?php

namespace App\Repositories;

use App\Exceptions\GlobalException;
use App\Models\Auction;
use App\Helpers\QueryConfig;
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
        $auctionQuery = Auction::query();

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

        $attributesToSet = ['title', 'description', 'starting_price', 'start_date', 'end_date'];

        $filteredAttributes = array_intersect_key($validated, array_flip($attributesToSet));
        $filteredAttributes['user_id'] = $user->id;
        $auction = Auction::create($filteredAttributes);

        if (!$auction) {
            throw new GlobalException('auction_creation_failed');
        }

        return $auction->toArray();
    }
}
