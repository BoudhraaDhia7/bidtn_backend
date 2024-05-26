<?php

namespace App\Repositories;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Support\Collection;

class StatisticsRepository
{
    /**
     * Get all user statistics.
     *
     * @param User $user
     * @return array
     */
    public static function userStats(User $user): array
    {   
 
        $totalBids = $user->transactions()->count();
        $totalCreatedAuctions = $user->auctions()->count();
        $totalWinnedAuctions = Auction::where('winner_id', $user->id)->count();


        $currentYear = date('Y', time());
        $auctionData = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthName = date('M', mktime(0, 0, 0, $month, 1));
            $startOfMonth = mktime(0, 0, 0, $month, 1, $currentYear);
            $endOfMonth = mktime(23, 59, 59, $month, date('t', $startOfMonth), $currentYear);

            $bidsCount = $user->transactions()
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
            $auctionsCount = $user->auctions()
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();

            $auctionData[] = [
                'name' => $monthName,
                'bids' => $bidsCount,
                'auctions' => $auctionsCount,
            ];
        }

        return [
            'total_bids' => $totalBids,
            'total_created_auctions' => $totalCreatedAuctions,
            'total_winned_auctions' => $totalWinnedAuctions,
            'auction_data' => $auctionData,
        ];
    }
}
