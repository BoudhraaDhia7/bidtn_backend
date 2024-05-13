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

class JoinAuctionController
{
    /**
     * Store a new auction in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    use GlobalResponse;

    private $auctionRepository;
    public function __construct(AuctionRepository $auctionRepository)
    {
        $this->auctionRepository = $auctionRepository;
    }

    public function __invoke($id)
    {
        $auction = Auction::findOrfail($id);
        $this->checkAuthrization($auction);
        try {
            $this->auctionRepository->joinAuction($auction, auth()->user());
            return $this->GlobalResponse('auctions_joined', Response::HTTP_OK);
        } catch (\Exception $e) {
            \Log::error('AuctionStoreController: Error retrieving auctions' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode($e->getCode()));
        }
    }

    /**
     * Check if the user is authorized to delete the auction
     */
    private function checkAuthrization($auction)
    {  
        $user = auth()->user();

        if ($user->cannot('joinAuction', [$auction, $user])) {
            abort($this->GlobalResponse('failed_to_join', Response::HTTP_UNAUTHORIZED));
        }
    }
}
