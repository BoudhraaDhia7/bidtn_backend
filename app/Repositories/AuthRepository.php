<?php
namespace App\Repositories;

use Exception;
use App\Models\User;
use App\Exceptions\AuthException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class AuthRepository
{
    /**
     * @param array $data
     * @return Response
     */
    public static function authenticate(array $credentials)
    {
        if (!($token = JWTAuth::attempt($credentials))) {
            throw new AuthException('Invalid credentials');
        }

        $user = auth()->user();

        $refreshToken = TokenRepository::generateRefreshToken($user);

        if (!$refreshToken) {
            throw new Exception('Token generation failed');
        }

        return [
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => 30,
            'user' => $user,
        ];
    }

    /**
     * @param array $data
     * @return Response
     */
    public static function register($email, $password, $first_name, $last_name, $optionalParams = [])
    {

        $password = Hash::make($password);
        $user = User::create([
            'email' => $email,
            'password' => $password,
            'first_name' => $first_name,
            'last_name' => $last_name,
        ]);

        if (!$user) {
            throw new Exception('User registration failed');
        }

        $token = JWTAuth::fromUser($user);

        if (!$token) {
            throw new Exception('Token generation failed');
        }

        $refreshToken = TokenRepository::generateRefreshToken($user);

        if (!$refreshToken) {
            throw new Exception();
        }

        $response = [
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => 30,
            'user' => $user,
        ];

        if (!empty($optionalParams['profile_picture'])) {
            $profilePicture = $optionalParams['profile_picture'];
            $storedPath = $profilePicture->store('profile_pictures', 'public');
            $fullUrl = Storage::url($storedPath);
            $mediaData = [
                'file_name' => $user->id . '_profile_picture',
                'file_path' => $fullUrl,
                'file_type' => $profilePicture->getClientMimeType(),
            ];


            MediaRepository::attachMediaToModel($user, $mediaData);

            $response['user']['profile_picture'] = $fullUrl;
        }

        return $response;
    }

    /**
     * @param array $data
     * @return Response
     */
    public static function logout()
    {
        JWTAuth::parseToken()->invalidate();
        return ['message' => 'User logout successfully'];
    }
}
