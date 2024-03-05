<?php

namespace App\Http\Controllers\Api\Authentification;

use OpenApi\Attributes as OA;
use App\Traits\GlobalResponse;;
use App\Exceptions\AuthException;
use App\Exceptions\GlobalException;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class RefreshTokenController extends Controller
{
    use GlobalResponse;

     /**
     * Refresh the authentication token.
     *
     * This endpoint is used to log out the user. It requires no parameters but a valid
     * bearer token must be provided in the Authorization header. On success, it returns
     * an HTTP 200 OK response with a message indicating successful logout.
     *
     * @throws GlobalException If an internal server error occurs during the logout process.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the result of the logout operation.
     */
    
    #[OA\SecurityScheme(
        securityScheme: "bearerAuth",
        type: "http",
        scheme: "bearer",
        bearerFormat: "JWT"
    )]

    #[OA\Post(
        path: "/api/user/refresh-token",
        tags: ["Auth"],
        summary: "Refresh Authentication Token",
        operationId: "refreshToken",
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: "Authentication token refreshed successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "token", type: "string", description: "The new authentication token."),
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: "Unauthorized. Token is invalid or expired.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "error", type: "string", description: "Error message explaining the reason for being unauthorized.")
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_INTERNAL_SERVER_ERROR,
                description: "Internal server error.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "error", type: "string", description: "Error message explaining the internal server error.")
                    ]
                )
            )
        ]
    )]

    public function __invoke()
    {
        try {
            $response = UserRepository::refreshToken();
            return $this->GlobalResponse('token_refreshed', Response::HTTP_OK, $response);
        }catch (AuthException $e){
            return $this->GlobalResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        } catch (GlobalException $e) {
            return $this->GlobalResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
