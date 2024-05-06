<?php
// AuctionController.php

namespace App\Http\Controllers\Api\Auction;

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
        $user = AuthHelper::currentUser();
        $this->checkAuthrization($auction, $user);
        try {
            $auction = AuctionRepository::deleteAuction($auction, $user);
            return $this->GlobalResponse('auctions_deleted', Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('AuctionStoreController: Error retrieving auctions' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode($e->getCode()));
        }
    }

    /**
     * Check if the user is authorized to delete the auction
     */
    private function checkAuthrization($auction)
    {   $user = auth()->user();
        if ($user->cannot('deleteAuction', [$user , $auction])) {
            return $this->GlobalResponse('fail_delete', Response::HTTP_UNAUTHORIZED);
        }
    }
}
