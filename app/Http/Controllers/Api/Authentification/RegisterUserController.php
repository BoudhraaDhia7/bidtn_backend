<?php

namespace App\Http\Controllers\Api\Authentification;

use App\Traits\SuccessResponse; 
use App\Traits\ErrorResponse;
use App\Exceptions\GlobalException;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\RegisterUserRequest;
use Symfony\Component\HttpFoundation\Response;

class RegisterUserController extends Controller
{
    use SuccessResponse;
    use ErrorResponse;

    private $userRepository;

    public function __invoke(RegisterUserRequest $request)
    {   
        // instantiate the UserRepository
        $this->userRepository = new UserRepository();
        try {
            $validated = $request->validated();
            $response = $this->userRepository->register(
                email: $validated['email'],
                password: $validated['password'],
                first_name: $validated['first_name'],
                last_name: $validated['last_name'],
                optionalParams: array_diff_key($validated, array_flip(['email', 'password', 'first_name', 'last_name']))
            );
            return $this->SuccessResponse('messages.token_refreshed', Response::HTTP_OK, $response);
        } catch (GlobalException) {
            return $this->ErrorResponse('internal_server_error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
