<?php

namespace App\Http\Controllers\Api\Authentification;


use App\Traits\SuccessResponse;
use App\Traits\ErrorResponse;
use App\Exceptions\GlobalException;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class LogoutUserController extends Controller
{
    use SuccessResponse;
    use ErrorResponse;
     
    private $userRepository;
    public function __invoke()
    {   
        // instantiate the UserRepository
        $this->userRepository = new UserRepository();
        try {
            userRepository::logout();
            return $this->SuccessResponse('messages.user_logout', Response::HTTP_OK);
        } catch (GlobalException) {
            return $this->ErrorResponse('internal_server_error', Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }
}
