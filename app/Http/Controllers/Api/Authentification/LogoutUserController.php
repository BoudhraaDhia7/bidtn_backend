<?php

namespace App\Http\Controllers\Api\Authentification;


use App\Traits\GlobalResponse;
use App\Exceptions\GlobalException;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class LogoutUserController extends Controller
{
    use GlobalResponse;
     
    public function __invoke()
    {   
        try {
            UserRepository::logout();
            return $this->GlobalResponse('user_logout', Response::HTTP_OK);
        } catch (GlobalException) {
            return $this->GlobalResponse('internal_server_error', Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }
}
