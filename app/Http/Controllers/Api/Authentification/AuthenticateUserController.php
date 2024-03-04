<?php

namespace App\Http\Controllers\Api\Authentification;

use App\Traits\GlobalResponse;
use App\Exceptions\GlobalException;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\AuthUserRequest;
use Symfony\Component\HttpFoundation\Response;


class AuthenticateUserController extends Controller
{   
    use GlobalResponse;
     
    private $userRepository;
    public function __invoke(AuthUserRequest $request)
    {   
        
        // instantiate the UserRepository
        $this->userRepository = new UserRepository();
        try {
            $response = $this->userRepository->authenticate($request->validated());
            return $this->GlobalResponse('user_authenticated', Response::HTTP_OK, $response);
        } catch (GlobalException $e) {
            return $this->GlobalResponse( $e->getMessage() , Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }
}
