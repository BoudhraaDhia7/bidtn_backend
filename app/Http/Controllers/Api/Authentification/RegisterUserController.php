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
        path: "/api/auth/register",
        tags: ["Authentification"],
        description: "Register a new user",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["first_name","last_name",  "email", "password"],
                properties: [
                    new OA\Property(property: "first_name", type: "string", example: "John"),
                    new OA\Property(property: "last_name", type: "string", example: "Doe"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "john.doe@test.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "strongpassword"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: "User registered successfully",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "message", type: "string", description: "Success message"),
                            new OA\Property(property: "data", type: "object", description: "Response data",
                                properties: [
                                    new OA\Property(property: "token", type: "string", description: "Authentication token"),
                                    new OA\Property(property: "data", type: "object", description: "User details",
                                        properties: [
                                            new OA\Property(property: "token_type", type: "string", description: "Token type"),
                                            new OA\Property(property: "expires_in", type: "integer", description: "Token expiration time"),
                                            new OA\Property(property: "user", type: "object", description: "User details",
                                                properties: [
                                                    new OA\Property(property: "id", type: "integer", description: "User ID"),
                                                    new OA\Property(property: "name", type: "string", description: "User name"),
                                                    new OA\Property(property: "email", type: "string", description: "User email"),
                                                    new OA\Property(property: "role_id", type: "integer", description: "User role"),
                                                    new OA\Property(property: "balance", type: "integer", description: "User balance"),
                                                    new OA\Property(property: "created_at", type: "string", description: "User creation date"),
                                                    new OA\Property(property: "updated_at", type: "string", description: "User update date"),
                                                    new OA\Property(property: "deleted_at", type: "string", nullable: true, description: "User deletion date if applicable")
                                                ]
                                            )
                                        ]
                                    )
                                ]
                            )
                        ]
                    )
                )
            ),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: "Invalid Request Data"),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: "Server Error")
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
