<?php

namespace App\Policies;

use App\Models\Auction;
use App\Models\User;

class AuctionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function deleteAuction(User $user, Auction $auction, $id)
    {
        if ($user->role_id === 1) {
            return true && !$auction->is_confirmed;
        }

        return $user->id === $auction->user_id && !$auction->is_confirmed;
    }

    public function updateAuction(User $user, Auction $auction, $id)
    {
        if ($user->role_id === 1) {
            return true && !$auction->is_confirmed;
        }

        return $user->id === $auction->user_id && !$auction->is_confirmed;
    }

    public function showAuction(User $user, Auction $auction, $id)
    {
        if ($user->role_id === 1) {
            return true;
        }

        return $user->id === $auction->user_id;
    }

    public function joinAuction(User $user, Auction $auction)
    {
        return $user->balance > $auction->starting_price && $auction->is_finished === 0 && $auction->is_confirmed === 1 && $auction->is_started === 0 && $auction->user_id != $user->id;
    }

    //check if the is a participant in the auction
    public function bidOnAuction(?User $user, Auction $auction, $bidAmount)
    {
        return $auction->isParticipant($user->id) && $auction->isHighestBid($bidAmount) && $auction->is_finished === 0 && $auction->is_confirmed === 1 && $auction->is_started === 1;
    }

    public function rejectAuction(?User $user , Auction $auction)
    {   
        return $user->isAdmin() && $auction->is_finished === 0 && $auction->is_confirmed === 0 && $auction->is_started === 0;
    }

    public function confirmAuction(?User $user , Auction $auction)
    {   
        return $user->isAdmin() && $auction->is_finished === 0 && $auction->is_confirmed === 0 && $auction->is_started === 0 && $auction->is_rejected === 0;
    }
}
