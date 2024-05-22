<?php

namespace App\Http\Controllers\Api\Auction;

use App\Http\Controllers\Controller;

use App\Models\Auction;
use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
use Illuminate\Http\JsonResponse;

class ShowAuctionActivityController extends Controller
{
    use GlobalResponse;

    public function __invoke($id): JsonResponse
    {
        $auction = Auction::findOrFail($id);
        $this->checkAuthrization($auction);
        return $this->GlobalResponse('auction_activity_retrieved', Response::HTTP_OK, $auction->transactions()->get());
    }
  
    /**
     * Check if the user is authorized to view the auction
     */
    private function checkAuthrization($auction)
    {   
        $user = auth()->user();
        if ($user->cannot('showAuctionActivity', [$auction, $user])) {
            abort($this->GlobalResponse('fail_show', Response::HTTP_UNAUTHORIZED));
        }
    }
}
