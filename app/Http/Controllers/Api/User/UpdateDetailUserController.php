<?php

namespace App\Http\Controllers\Api\User;

use OpenApi\Attributes as OA;
use App\Traits\GlobalResponse;
use Illuminate\Http\JsonResponse;
use App\Exceptions\GlobalException;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\UpdateUserDetailRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateDetailUserController extends Controller
{
    use GlobalResponse;

    private $userRepository;

    /**
     * Update an existing user.
     *
     * This endpoint is responsible for updating an existing user based on the provided details in the request body.
     * It performs validation on the request data and, if successful, updates the user account and optionally the profile picture,
     * returning a success message along with the updated user details. This process can result in various responses depending on the
     * outcome of the update attempt.
     *
     * @param UpdateUserRequest $request The request object containing the user details
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[OA\Put(
        path: "/api/users/update-detail",
        tags: ["User"],
        summary: "Update user details",
        description: "Updates the authenticated user's details including first name, last name, and optionally the password and profile picture.",
        requestBody: new OA\RequestBody(
            description: "User details and profile picture to update",
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: "multipart/form-data",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "first_name",
                                type: "string",
                                description: "First name of the user"
                            ),
                            new OA\Property(
                                property: "last_name",
                                type: "string",
                                description: "Last name of the user"
                            ),
                            new OA\Property(
                                property: "password",
                                type: "string",
                                description: "New password for the user",
                                format: "password"
                            ),
                            new OA\Property(
                                property: "profile_picture",
                                type: "string",
                                format: "binary",
                                description: "Profile picture file"
                            )
                        ],
                        required: []
                    )
                )
            ]
        ),
        responses: [
            new OA\Response(
                response: "200",
                description: "User updated successfully",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "success",
                                type: "boolean",
                                description: "Indicates if the update was successful"
                            ),
                            new OA\Property(
                                property: "message",
                                type: "string",
                                description: "Message about the result of the operation"
                            ),
                            new OA\Property(
                                property: "user",
                                type: "object",
                                description: "Updated user details",
                                properties: [
                                    new OA\Property(
                                        property: "first_name",
                                        type: "string",
                                        description: "First name of the user"
                                    ),
                                    new OA\Property(
                                        property: "last_name",
                                        type: "string",
                                        description: "Last name of the user"
                                    ),
                                    new OA\Property(
                                        property: "profile_picture",
                                        type: "string",
                                        description: "URL of the new profile picture"
                                    )
                                ]
                            )
                        ]
                    )
                )
            ),
            new OA\Response(
                response: "401",
                description: "Unauthorized if the user is not authenticated",
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
                        ]
                    )
                )
            )
        ]
    )]
    
    public function __invoke(UpdateUserDetailRequest $request) : JsonResponse
    {   
        $this->userRepository = new UserRepository();

        try {
            $user = auth()->user();
            $validated = $this->getAttributes($request);
            $response = $this->userRepository->updateUserDetail($validated, $user);
            return $this->GlobalResponse('user_updated', Response::HTTP_OK, $response);
        } catch (GlobalException $e) {
            Log::error('UpdateUserDetail: Error updating user'.$e->getMessage());
            return $this->GlobalResponse('user_provided_invalid', Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            Log::error('UpdateUserDetail: Error updating user'. $e->getMessage());
            return $this->GlobalResponse('general_error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getAttributes(UpdateUserDetailRequest $request): array
    {
        return $request->validated();
    }
}
