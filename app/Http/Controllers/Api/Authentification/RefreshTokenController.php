<?php

namespace App\Http\Controllers\Api\Authentification;

use App\Traits\GlobalResponse;;
use App\Exceptions\GlobalException;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class RefreshTokenController extends Controller
{
    use GlobalResponse;


    public function __invoke()
    {
        try {
            $response = UserRepository::refreshToken();
            return $this->GlobalResponse('user_registred', Response::HTTP_OK, $response);
        } catch (GlobalException $e) {
            return $this->GlobalResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
