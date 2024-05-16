<?php

namespace App\Http\Controllers\Api\Auction;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Repositories\AuctionRepository;
use App\Traits\GlobalResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class EndAuctionController extends Controller
{   
    use GlobalResponse;

    public function __invoke($id)
    {   
        $auction = Auction::findOrfail((int) $id);
        try {
            AuctionRepository::auctionWinner($auction);
            return $this->GlobalResponse('auction_ended', Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('AuctionFinnish: Error ending auctions' . $e->getMessage());
            return $this->GlobalResponse($e->getCode(), ResponseHelper::resolveStatusCode(500));
        }
    }
}
