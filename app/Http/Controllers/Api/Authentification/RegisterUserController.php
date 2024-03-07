<?php

namespace App\Http\Controllers\Api\Authentification;

use OpenApi\Attributes as OA;
use App\Traits\GlobalResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\AuthRepository;
use App\Http\Requests\RegisterUserRequest;
use Symfony\Component\HttpFoundation\Response;

class RegisterUserController extends Controller
{
    use GlobalResponse;

    private $authRepository;
    /**
     * Register a new user.
     *
     * This endpoint is responsible for registering a new user based on the provided details in the request body.
     * It performs validation on the request data and, if successful, creates a new user account and returns an
     * authentication token along with user details. This process can result in various responses depending on the
     * outcome of the registration attempt.
     *
     * @param RegisterUserRequest $request The request object containing the user details
     * @return \Symfony\Component\HttpFoundation\Response
     */

     #[OA\Post(
        path: "/api/user/register",
        tags: ["Auth"],
        summary: "Register New User",
        operationId: "registerUser",
        requestBody: new OA\RequestBody(
        description: "User registration details with profile picture",
        required: true,
        content: new OA\MediaType(
            mediaType: "multipart/form-data",
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: "email", type: "string", description: "User email address."),
                    new OA\Property(property: "password", type: "string", description: "User password."),
                    new OA\Property(property: "first_name", type: "string", description: "User's first name."),
                    new OA\Property(property: "last_name", type: "string", description: "User's last name."),
                    new OA\Property(property: "profile_picture", type: "string", format: "binary", description: "Profile picture file."),
                ],
                required: ["email", "password", "first_name", "last_name"]
            )
        ),
    ),
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: "User registered successfully. Authentication token refreshed.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "token", type: "string", description: "The new authentication token."),
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_UNPROCESSABLE_ENTITY,
                description: "Unprocessable Entity. Issues with user registration details."
            ),
            new OA\Response(
                response: Response::HTTP_INTERNAL_SERVER_ERROR,
                description: "Internal server error or other unspecified error."
            )
        ]
    )]
    public function __invoke(RegisterUserRequest $request) : JsonResponse
    {
        // instantiate the UserRepository
        $this->authRepository = new AuthRepository();
        try {
            $validated = $this->getAttributes($request);
            $response = $this->authRepository->register(email: $validated['email'], password: $validated['password'], first_name: $validated['first_name'], last_name: $validated['last_name'], optionalParams: array_diff_key($validated, array_flip(['email', 'password', 'first_name', 'last_name'])));
            return $this->GlobalResponse('user_registred', Response::HTTP_OK, $response);
        }  catch (\Exception $e) {
            Log::error('RegisterUserController: Error registering user'. $e->getMessage());
            return $this->GlobalResponse('general_error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getAttributes(RegisterUserRequest $request): array
    {
        return $request->validated();
    }
}
