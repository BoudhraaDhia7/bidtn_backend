<?php
namespace App\Repositories;

use App\Models\User;
use App\Exceptions\AuthException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Exceptions\GlobalException;
use Exception;


class TokenRepository
{
    /**
     * @param Request
     * @return Response
     */
    public static function refreshAccessToken($user = null)
    {
        if (empty($user)) {
            throw new GlobalException('No authenticated user found.');
        }

        $refreshToken = JWTAuth::getToken();
        if (empty($refreshToken)) {
            throw new AuthException('No refresh token provided.');
        }

        $payload = JWTAuth::getPayload($refreshToken);
        if ($payload->get('token_type') !== 'refresh_token') {
            throw new Exception('Invalid token type, expected a refresh token.');
        }

        $newToken = JWTAuth::claims(['token_type' => 'access_token'])->fromUser($user);

        return [
            'access_token' => $newToken,
            'refresh_token' => (string) $refreshToken,
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
