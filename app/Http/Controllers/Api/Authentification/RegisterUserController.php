<?php

namespace App\Http\Controllers\Api\Authentification;

use App\Traits\GlobalResponse;
use App\Exceptions\GlobalException;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\RegisterUserRequest;

class RegisterUserController extends Controller
{
    use GlobalResponse;

    private $userRepository;

    public function __invoke(RegisterUserRequest $request)
    {
        // instantiate the UserRepository
        $this->userRepository = new UserRepository();

        try {
            $response = $this->userRepository->register($request->validated());
            return $this->GlobalResponse('User registred successfully', 200, $response);
        } catch (GlobalException $e) {
            return Response::json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
