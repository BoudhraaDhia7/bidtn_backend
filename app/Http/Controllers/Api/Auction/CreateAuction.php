<?php
// AuctionController.php

namespace App\Http\Controllers\Api\Auction;

use App\Helpers\ResponseHelper;
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
     
        try {
            $validated = $this->getAttributes($request);
            $user = auth()->user();
            $auction = AuctionRepository::createAuction($validated , $user);
            return $this->GlobalResponse('auctions_created', Response::HTTP_OK, $auction);
        } catch (\Exception $e) {
            \Log::error('AuctionStoreController: Error retrieving auctions' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode($e->getCode()));
        }
    }

    private function getAttributes(StoreAuctionRequest $request): array
    {
        return $request->validated();
    }
}

