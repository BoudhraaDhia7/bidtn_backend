<?php

namespace App\Http\Controllers\Api\Authentification;

use App\Traits\SuccessResponse;
use App\Traits\ErrorResponse;
use App\Exceptions\GlobalException;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class RefreshTokenController extends Controller
{
    use SuccessResponse;
    use ErrorResponse;

    private $userRepository;

    public function __invoke()
    {
        // instantiate the UserRepository
        $this->userRepository = new UserRepository();

        try {
            $response = userRepository::refreshToken();
            return $this->SuccessResponse('messages.user_registred', Response::HTTP_OK, $response);
        } catch (GlobalException $e) {
            return $this->ErrorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
