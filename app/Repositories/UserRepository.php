<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use App\Exceptions\AuthException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Exceptions\GlobalException;
use App\Exceptions\AddUserException;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\AddMediaException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Exceptions\JWTException;


class UserRepository
{
    /**
     * @param array $data
     * @return Response
     */
    public function authenticate(array $credentials)
    {
        try {
            if (!($token = JWTAuth::attempt($credentials))) {
                throw new Exception('user_authenticated_failed', 401);
            }
            $refreshToken = $this->generateRefreshToken(auth()->user());
            $user = auth()->user();
            return [
                'access_token' => $token,
                'refresh_token' => $refreshToken,
                'token_type' => 'bearer',
                'expires_in' => 30,
                'user' => $user,
            ];
        } catch (JWTException $e) {
            throw new GlobalException($e->getMessage());
        } catch (\Exception $e) {
            throw new AuthException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param array $data
     * @return Response
     */
    public function register($email, $password, $first_name, $last_name, $optionalParams = [])
    {
        try {
            $password = Hash::make($password);
            $user = User::create([
                'email' => $email,
                'password' => $password,
                'first_name' => $first_name,
                'last_name' => $last_name,
            ]);

            $token = JWTAuth::fromUser($user);
            $refreshToken = $this->generateRefreshToken($user);

            $response = [
                'access_token' => $token,
                'refresh_token' => $refreshToken,
                'token_type' => 'bearer',
                'expires_in' => 30,
                'user' => $user,
            ];

            $profilePicture = $data['profile_picture'] ?? null;

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
        } catch (AddMediaException) {
            throw new AddMediaException();
        } catch (QueryException) {
            throw new AddUserException();
        } catch (JWTException) {
            throw new GlobalException('token_generation_failed');
        } catch (\Exception) {
            throw new GlobalException('internal_server_error');
        }
    }

    /**
     * @param array $data
     * @return Response
     */
    public static function logout()
    {
        try {
            JWTAuth::parseToken()->invalidate();
            return ['message' => 'User logout successfully'];
        } catch (JWTException $e) {
            throw new GlobalException($e->getMessage());
        } catch (\Exception $e) {
            throw new GlobalException($e->getMessage());
        }
    }

    /**
     * @param Request
     * @return Response
     */

    public static function refreshToken()
    {   
        try {
            $refreshToken = JWTAuth::getToken();
            $user = auth()->user();

            $payload = JWTAuth::getPayload($refreshToken);
            if ($payload->get('type') !== 'refresh_token') {
                throw new AuthException('token_invalid');
            }

            $newToken = JWTAuth::refresh(false, true);

            return [
                'access_token' => $newToken,
                'refresh_token' => $refreshToken->get(),
                'user' => $user,
            ];
        } catch (AuthException $e) {
            throw new AuthException();
        } catch (JWTException $e) {
            throw new GlobalException('token_generation_failed');
        }
    }

    /**
     * @param User $user
     * @return string
     */
    private function generateRefreshToken($user)
    {
        JWTAuth::factory()->setTTL(10080);
        $customClaims = ['type' => 'refresh_token'];
        $refreshToken = JWTAuth::claims($customClaims)->fromUser($user);
        return $refreshToken;
    }
}
