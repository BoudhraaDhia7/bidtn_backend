<?php

namespace App\Http\Controllers\Api\Authentification;

use OpenApi\Attributes as OA;
use App\Traits\GlobalResponse;
use App\Exceptions\AuthException;
use App\Exceptions\GlobalException;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\AuthUserRequest;
use Symfony\Component\HttpFoundation\Response;

#[OA\Info(title: "Laravel API", version: "1.0.0", description: "This is a simple API for a user authentication")]
class AuthenticateUserController extends Controller
{   
    use GlobalResponse;

    private $userRepository;

    /**
     * Authenticate a user.
     *
     * This endpoint is responsible for authenticating a user based on the provided email and password.
     * It performs validation on the request data and, if successful, returns an authentication token
     * along with user details. This process can result in various responses depending on the outcome
     * of the authentication attempt.
     *
     * @param AuthUserRequest $request The request object containing the user credentials.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    #[OA\Post(
        path: "/api/user/login",
        tags: ["Auth"],
        description: "Authenticate a user",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "user@test.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "password")
                ]
            )
        ),
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: "Authenticated successfully"),
            new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
        ]
    )]
    public function __invoke(AuthUserRequest $request)
    {
        // instantiate the UserRepository
        $this->userRepository = new UserRepository();
        try {
            $response = $this->userRepository->authenticate($request->validated());
            return $this->GlobalResponse('user_authenticated', Response::HTTP_OK, $response);
        }catch (AuthException $e){
            return $this->GlobalResponse($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        } catch (GlobalException $e) {
            return $this->GlobalResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
