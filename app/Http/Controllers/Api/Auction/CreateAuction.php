<?php
// AuctionController.php

namespace App\Http\Controllers\Api\Auction;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
use App\Repositories\AuctionRepository;
use App\Http\Requests\StoreAuctionRequest;

class CreateAuction
{
    /**
     * Store a new auction in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    use GlobalResponse;
    
    public function __invoke(StoreAuctionRequest $request)
    {
        $validated = $this->getAttributes($request);
        $user = auth()->user();
        $auction = AuctionRepository::createAuction($validated , $user);
        try {
          
            return $this->GlobalResponse('auctions_created', Response::HTTP_OK, $auction);
        } catch (\Exception $e) {
            \Log::error('AuctionStoreController: Error retrieving auctions' . $e->getMessage());
            return $this->GlobalResponse('general_error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getAttributes(StoreAuctionRequest $request): array
    {
        return $request->validated();
    }
}

