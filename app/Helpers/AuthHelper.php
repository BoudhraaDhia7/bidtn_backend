<?php
namespace App\Helpers;

use App\Exceptions\GlobalException;
use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    /**
     * Get the current authenticated user's details as an object.
     *
     * @return object
     */
    public static function CurrentUser()
    {   
        $user = Auth::user();
        if(!$user){
            throw new GlobalException('unauthorized', 401);
        }
        return (object) [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'isAdmin' => $user->role->id === 1,
            'role' => $user->role,
        ];
    }
}
