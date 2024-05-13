<?php

namespace App\Http\Controllers\Api\Auction;

use App\Helpers\AuthHelper;
use OpenApi\Attributes as OA;

use App\Models\Auction;
use App\Models\AuctionParticipant;
use App\Repositories\AuctionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
use App\Traits\PaginationParams;
use Illuminate\Http\JsonResponse;

class ShowAuctionCurrentStateController
{
    use GlobalResponse;
    use PaginationParams;

    /**
     * Get all auctions from the database.
        * @param Request $request
        * @return JsonResponse
            
     */

    public function __invoke($id): JsonResponse
    {
        $auction = Auction::findOrFail($id);
        $this->checkAuthrization($auction);
        AuctionParticipant::where('user_id', auth()->id())->where('auction_id', $id)->firstOrFail();

        try {
            $response = AuctionRepository::showAuctionCurrentState($auction, auth()->user());
            return $this->GlobalResponse('auction_retrieved', Response::HTTP_OK, [$response]);
        } catch (\Exception $e) {
            return $this->GlobalResponse('auction_retrieved', Response::HTTP_OK, $e->getMessage());
        }
    }

    /**
     * Check if the user is authorized to view the auction
     */
    private function checkAuthrization($auction)
    {
        // $user = auth()->user();
        // if ($user->cannot('showAuction', [$auction, $user])) {
        //     abort($this->GlobalResponse('fail_show', Response::HTTP_UNAUTHORIZED));
        // }
    }
}
