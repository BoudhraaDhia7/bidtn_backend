<?php

namespace App\Http\Controllers\Api\Auction;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Repositories\AuctionRepository;
use App\Traits\GlobalResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class RejectAuctionController extends Controller
{
    use GlobalResponse;

    /**
     * Reject a new created auction in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($id)
    {
        $auction = Auction::findOrfail($id);
        $this->checkAuthrization($auction);
        try {
            AuctionRepository::rejectAuction($auction, auth()->user());
            return $this->GlobalResponse('auctions_rejected', Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('RejectAuctionController: Error rejecting auctions' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode($e->getCode()));
        }
    }

    /**
     * Check if the user is authorized to delete the auction
     */
    private function checkAuthrization($auction)
    {
        $user = auth()->user();

        if ($user->cannot('rejectAuction', [$auction])) {
            abort($this->GlobalResponse('failed_to_reject', Response::HTTP_UNAUTHORIZED));
        }
    }
}
