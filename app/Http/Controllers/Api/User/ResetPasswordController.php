<?php

namespace App\Http\Controllers\Api\User;

use App\Exceptions\GlobalException;
use Exception;
use OpenApi\Attributes as OA;
use App\Traits\GlobalResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController extends Controller
{
    use GlobalResponse;
    /**
     * Reset User Password.
     *
     * This endpoint is responsible for resetting a user's password based on the provided token and new password details.
     * It performs validation on the request data and, if successful, updates the user's password.
     * This process can result in various responses depending on the outcome of the password reset attempt.
     *
     * @param ResetPasswordRequest $request
     * @param string $resetPasswordToken Token required to validate the password reset request.
     *
     * @return JsonResponse
     */

    #[OA\Post(path: '/api/password/reset/{token}', tags: ['User'], description: "Resets a user's password using a provided token along with the new password and its confirmation.", requestBody: new OA\RequestBody(description: 'New password and its confirmation', required: true, content: [new OA\MediaType(mediaType: 'application/json', schema: new OA\Schema(type: 'object', properties: [new OA\Property(property: 'password', type: 'string', format: 'password', description: 'New password'), new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', description: 'New password confirmation')], required: ['password', 'password_confirmation']))]), responses: [new OA\Response(response: 200, description: 'Password reset successfully'), new OA\Response(response: 401, description: 'Unauthorized'), new OA\Response(response: 422, description: 'Unprocessable Entity')])]
    public function __invoke(ResetPasswordRequest $request, $resetPasswordToken): JsonResponse
    {
        try {
            $validated = $this->getAttributes($request);
            $response = UserRepository::resetPassword($validated, $resetPasswordToken);
            return $this->GlobalResponse('user_password_reset', Response::HTTP_OK, $response);
        } catch (GlobalException $e) {
            Log::error('GetUserController: Error getting user' . $e->getMessage());
            return $this->GlobalResponse('invalid_link', Response::HTTP_UNAUTHORIZED);
        } catch (Exception $e) {
            Log::error('GetUserController: Error getting user' . $e->getMessage());
            return $this->GlobalResponse('general_error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getAttributes(ResetPasswordRequest $request): array
    {
        return $request->validated();
    }
}
