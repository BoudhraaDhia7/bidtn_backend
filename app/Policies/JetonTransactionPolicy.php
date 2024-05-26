<?php

namespace App\Policies;

use App\Models\User;

class JetonTransactionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether can list the transactions
     *
     * @param User $user
     * @return bool
     */
    public function listTransaction(?User $user): bool
    {
        return $user->isAdmin();
    }

    public function exchangeJeton(?User $user , int $amount)
    {   
        return $user->balance >= $amount && $amount >= 100;
    }
}
