<?php
namespace App\Repositories;

use App\Models\User;
use App\Exceptions\AuthException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Exceptions\GlobalException;
use Exception;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;

class TokenRepository
{
    /**
     * @param Request
     * @return Response
     */
    public static function refreshAccessToken()
    {
        $refreshToken = JWTAuth::getToken();
        
        if (!$refreshToken) {
            throw new AuthException();
        }

        $user = auth()->user();
        $payload = JWTAuth::getPayload($refreshToken);
        if ($payload->get('token_type') !== 'refresh_token') {
            throw new Exception('Invalid token type');
        }

        $newToken = JWTAuth::claims(['token_type' => 'access_token'])->fromUser($user);

        return [
            'access_token' => $newToken,
            'refresh_token' => $refreshToken->get(),
            'user' => $user,
        ];
    }

    /**
     * @param User $user
     * @return string
     */
    public static function generateRefreshToken($user)
    {
        JWTAuth::factory()->setTTL(10080);
        $customClaims = ['token_type' => 'refresh_token'];
        $refreshToken = JWTAuth::claims($customClaims)->fromUser($user);
        return $refreshToken;
    }
}
