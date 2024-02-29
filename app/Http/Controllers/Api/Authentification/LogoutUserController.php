<?php

namespace App\Http\Controllers\Api\Authentification;

use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
use App\Exceptions\GlobalException;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class LogoutUserController extends Controller
{
    use GlobalResponse;
     
    private $userRepository;
    public function __invoke()
    {   
        // instantiate the UserRepository
        $this->userRepository = new UserRepository();
        try {
            $this->userRepository->logout();
            return $this->GlobalResponse('User logout successfully', 200);
        } catch (GlobalException $e) {
            return Response::json(['error' => $e->getMessage()], $e->getCode());
        } 
    }
}
