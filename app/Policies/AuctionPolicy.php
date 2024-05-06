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
}
