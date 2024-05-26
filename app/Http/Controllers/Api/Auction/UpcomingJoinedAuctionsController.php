<?php

namespace App\Http\Controllers\Api\Auction;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Repositories\AuctionRepository;
use App\Traits\GlobalResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UpcomingJoinedAuctionsController extends Controller
{
    use GlobalResponse;

    /**
     * Get upcoming auctions joined by the authenticated user.
     *
     * @return JsonResponse
     */

    public function __invoke(): JsonResponse
    {
        $user = auth()->user();
        try {
            $upcomingAuctions = AuctionRepository::upcomingJoinedAuctions($user);
            return $this->GlobalResponse('upcoming_auctions_retrieved', Response::HTTP_OK, $upcomingAuctions);
        } catch (\Exception $e) {
            Log::error('UpcomingJoinedAuctionsController: Error retrieving ' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode(500));
        }
    }
}
