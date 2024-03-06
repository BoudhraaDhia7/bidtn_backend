<?php

namespace App\Http\Controllers\Api\Authentification;


use Exception;
use OpenApi\Attributes as OA;
use App\Traits\GlobalResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\AuthRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class LogoutUserController extends Controller
{
    use GlobalResponse;
    

    /**
     * Invoke method for handling user logout.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the result of the logout operation.
     */

     
    #[OA\Get(
        path: "/api/user/logout",
        tags: ["Auth"],
        description: "Logout a user. This endpoint requires a Bearer token in the Authorization header.",
        responses: [
            new OA\Response(
                response: Response::HTTP_OK, 
                description: "User logged out successfully"
            ),
            new OA\Response(
                response: Response::HTTP_INTERNAL_SERVER_ERROR, 
                description: "Server Error"
            )
        ],
    )]
    
    public function __invoke() : JsonResponse
    {   
        try {
            AuthRepository::logout();
            return $this->GlobalResponse('user_logout', Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('LogoutUserController: Error logging out user'. $e->getMessage());
            return $this->GlobalResponse('internal_server_error', Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }
}
