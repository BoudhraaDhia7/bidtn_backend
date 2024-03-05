<?php

namespace App\Http\Controllers\Api\Authentification;


use App\Traits\GlobalResponse;
use App\Exceptions\GlobalException;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;


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
