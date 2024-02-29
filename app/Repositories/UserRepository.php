<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Exceptions\GlobalException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserRepository
{
    private $mediaRepository;
    /**
     * @param array $data
     * @return Response
     */
    public function authenticate(array $credentials)
    {
        try {
            if (!($token = JWTAuth::attempt($credentials))) {
                throw new Exception('Authentication failed.', 401);
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
            throw new GlobalException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            throw new GlobalException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param array $data
     * @return Response
     */
    public function register(array $data)
    {   

        try {
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);
            
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
            if ($profilePicture) {
                // initiate the mediaRepository
                $this->mediaRepository = new MediaRepository();
                $storedPath = $profilePicture->store('profile_pictures', 'public');
                $fullUrl = Storage::url($storedPath);
                $mediaData = [
                    'file_name' => $user->id . '_profile_picture',
                    'file_path' => $fullUrl,
                    'file_type' => $profilePicture->getClientMimeType(),
                ];
                $this->mediaRepository->attachMediaToModel($user, $mediaData);
                $response['user']['profile_picture'] = $fullUrl;
            }

            return $response;
        } catch (JWTException $e) {
            throw new GlobalException($e->getMessage(), '500');
        } catch (\Exception $e) {
            //dd($e->getMessage());
            throw new GlobalException($e->getMessage(), '500');
        }
    }

    /**
     * @param array $data
     * @return Response
     */
    public function logout()
    {
        try {
            JWTAuth::parseToken()->invalidate();
            return ['message' => 'User logout successfully'];
        } catch (JWTException $e) {
            throw new GlobalException($e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            throw new GlobalException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Request
     * @return Response
     */

     public function refreshToken()
     {
         try {
             $refreshToken = JWTAuth::getToken();
             $user = auth()->user();   
             
             $payload = JWTAuth::getPayload($refreshToken);
             if ($payload->get('type') !== 'refresh_token') {
                 throw new GlobalException('Invalid token type.' , 401);
             }
 
             $newToken = JWTAuth::refresh(false, true);

             return [
                 'access_token' => $newToken,
                 'refresh_token' => $refreshToken->get(),
                 'user'=> $user
             ];
            } catch (QueryException $e) {
                Log::error('Token revoke failed : ' . $e->getMessage());
                throw new GlobalException('Revoke token failed due to a server error. Please try again later.',500);
            } catch (GlobalException $e) {
                throw new GlobalException($e->getMessage(),500);
            }
     }

    /**
     * @param User $user
     * @return string
     */
    public function generateRefreshToken($user)
    {
        JWTAuth::factory()->setTTL(10080);
        $customClaims = ['type' => 'refresh_token'];
        $refreshToken = JWTAuth::claims($customClaims)->fromUser($user);
        return $refreshToken;
    }
}
