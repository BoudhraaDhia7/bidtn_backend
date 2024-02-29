<?php

namespace App\Http\Controllers\Api\Authentification;

use App\Traits\GlobalResponse;
use App\Exceptions\GlobalException;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Response;

class RefreshTokenController extends Controller
{
    use GlobalResponse;

    private $userRepository;

    public function __invoke()
    {
        // instantiate the UserRepository
        $this->userRepository = new UserRepository();

        try {
            $response = $this->userRepository->refreshToken();
            return $this->GlobalResponse('Token refreshed successfully', 200, $response);
        } catch (GlobalException $e) {
            return Response::json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}
