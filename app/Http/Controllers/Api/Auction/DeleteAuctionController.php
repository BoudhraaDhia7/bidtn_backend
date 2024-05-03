<?php
// AuctionController.php

namespace App\Http\Controllers\Api\Auction;

use App\Exceptions\GlobalException;
use App\Helpers\AuthHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
use App\Helpers\ResponseHelper;
use App\Models\Auction;
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
        $auction = Auction::findOrfail($id);
        try {
            $user = AuthHelper::currentUser();
            if (!$auction) {
                throw new GlobalException('auction_not_found', 404);
            }

            if ($auction->user_id !== auth()->user()->id && !$user->isAdmin) {
                throw new GlobalException('auction_unauthorized', 401);
            }

            if ($auction->is_confirmed) {
                throw new GlobalException('auction_allready_confirmed', 401);
            }

            $user = AuthHelper::currentUser();
            $auction = AuctionRepository::deleteAuction($auction, $user);
            return $this->GlobalResponse('auctions_created', Response::HTTP_OK, $auction);
        } catch (\Exception $e) {
            \Log::error('AuctionStoreController: Error retrieving auctions' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode($e->getCode()));
        }
    }
}
