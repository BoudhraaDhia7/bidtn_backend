<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use OpenApi\Attributes as OA;
use App\Traits\GlobalResponse;
use Illuminate\Http\JsonResponse;
use App\Exceptions\GlobalException;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\ForgotPasswordRequest;
use Symfony\Component\HttpFoundation\Response;

class ForgotPasswordController extends Controller
{   
    use GlobalResponse;
    #[OA\Post(
        path: "/api/password/forgot",
        tags: ["User"],
        summary: "Forgot Password",
        description: "Initiates a password reset process for the user identified by their email. It sends a reset link to the provided email address.",
        requestBody: new OA\RequestBody(
            description: "Email of the user requesting password reset",
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "email", type: "string", format: "email", description: "User's email address"),
                        ],
                        required: ["email"]
                    )
                )
            ]
        ),
        responses: [
            new OA\Response(response: 200, description: "Password reset email sent successfully"),
            new OA\Response(response: 404, description: "User not found"),
            new OA\Response(response: 422, description: "Unprocessable Entity"),
            new OA\Response(response: 500, description: "Internal server error")
        ]
    )]
    public function __invoke(ForgotPasswordRequest $request) : JsonResponse
    {
        try {
            $validated = $this->getAttributes($request);
            $response = UserRepository::forgotPassword($validated);
            return $this->GlobalResponse('email_sended', Response::HTTP_OK, $response);
        }
        catch (GlobalException $e){
            Log::error('ForgotPasswordController: Error getting user'. $e->getMessage());
            return $this->GlobalResponse('user_not_found', Response::HTTP_NOT_FOUND);
        }catch (Exception $e) {
            Log::error('ForgotPasswordController: Error getting user'. $e->getMessage());
            return $this->GlobalResponse('general_error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getAttributes(ForgotPasswordRequest $request): array
    {
        return $request->validated();
    }
}