<?php

namespace App\Policies;

use App\Models\JetonPack;
use App\Models\User;

class JetonPackPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function createJetonPack(?User $user)
    {
        return $user->isAdmin();    
    }

    public function deleteJetonPack(?User $user)
    {
        return $user->isAdmin();
    }

    public function updateJetonPack(?User $user)
    {
        return $user->isAdmin();
    }
  
}
