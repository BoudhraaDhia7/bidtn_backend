<?php

namespace App\Http\Controllers\Api\Authentification;

use App\Traits\GlobalResponse;
use App\Exceptions\GlobalException;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\AuthUserRequest;
use Illuminate\Support\Facades\Response;


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
            return $this->GlobalResponse('User authenticated successfully', 200, $response);
        } catch (GlobalException $e) {
            return Response::json(['error' => $e->getMessage()], $e->getCode());
        } 
    }
}
