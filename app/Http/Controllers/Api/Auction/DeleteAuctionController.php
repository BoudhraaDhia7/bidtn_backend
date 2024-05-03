<?php
// AuctionController.php

namespace App\Http\Controllers\Api\Auction;


use App\Helpers\AuthHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
use App\Helpers\ResponseHelper;
use App\Repositories\AuctionRepository;

class DeleteAuctionController
{
    /**
     * Store a new auction in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    use GlobalResponse;

    public function __invoke($id)
    {        
        try {
            $user = AuthHelper::currentUser();
            $auction = AuctionRepository::deleteAuction($id, $user);
            return $this->GlobalResponse('auctions_created', Response::HTTP_OK, $auction);
        } catch (\Exception $e) {
            \Log::error('AuctionStoreController: Error retrieving auctions' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode($e->getCode()));
        }
    }
}

