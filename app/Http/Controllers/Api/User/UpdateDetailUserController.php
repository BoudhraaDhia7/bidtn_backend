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
    #[OA\Patch(
        path: "/api/user/update/detail",
        tags: ["Auth"],
        summary: "Update Existing User",
        operationId: "updateUser",
        requestBody: new OA\RequestBody(
            description: "User update details with optional profile picture",
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
                )
            ),
        ),
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: "User updated successfully.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", description: "Success message."),
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_UNPROCESSABLE_ENTITY,
                description: "Unprocessable Entity. Issues with user update details."
            ),
            new OA\Response(
                response: Response::HTTP_INTERNAL_SERVER_ERROR,
                description: "Internal server error or other unspecified error."
            )
        ]
    )]
    public function __invoke(UpdateUserDetailRequest $request) : JsonResponse
    {
        $this->userRepository = new UserRepository();
        
        try {
            $validated = $this->getAttributes($request);
            $response = $this->userRepository->updateUserDetail($validated);
            return $this->GlobalResponse('user_updated', Response::HTTP_OK, $response);
        } catch (GlobalException $e) {
            Log::error('UpdateUserDetail: Error updating user'.$e->getMessage());
            return $this->GlobalResponse('user_authenticated_failed', Response::HTTP_INTERNAL_SERVER_ERROR);
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
