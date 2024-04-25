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
        path: "/api/auth/logout",
        tags: ["Auth"],
        summary: "Log out a user",
        description: "Logs out the current user by invalidating the authentication token. This endpoint requires an authenticated user with an active session.",
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: "200",
                description: "User logged out successfully",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "message",
                                type: "string",
                                description: "Confirmation message that the user has been logged out."
                            )
                        ],
                        example: [
                            "message" => "User logged out successfully"
                        ]
                    )
                )
            ),
            new OA\Response(
                response: "401",
                description: "Unauthorized if the token is invalid or expired",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "error",
                                type: "string",
                                description: "Error message explaining the nature of the authentication error."
                            )
                        ],
                        example: [
                            "error" => "Invalid token or token expired"
                        ]
                    )
                )
            ),
            new OA\Response(
                response: "500",
                description: "Internal server error",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "error",
                                type: "string",
                                description: "Error message explaining the nature of the server error."
                            )
                        ],
                        example: [
                            "error" => "Unable to process request due to server error"
                        ]
                    )
                )
            )
        ]
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
