<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use App\Traits\GlobalResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class GetUserController extends Controller
{   
    use GlobalResponse;

    /**
     * Get a user.
     *
     * This endpoint is responsible for Getting a user based on the provided ID.
     * It performs validation on the request data and, if successful, returns an a user details.
     * This process can result in various responses depending on the outcome of the Getting user attempt.
     * @param Request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    #[OA\Post(
        path: "/api/user/{id}",
        tags: ["User"],
        description: "Get a user",
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: "User details retrieved successfully"),
            new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: "Unauthorized"),
            new OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: "Unprocessable Entity"),
        ]
    )]
    public function __invoke() : JsonResponse
    {
        try {
            $response = UserRepository::getUser();
            return $this->GlobalResponse('user_detail', Response::HTTP_OK, $response);
        }
        catch (Exception $e) {
            Log::error('GetUserController: Error getting user'. $e->getMessage());
            return $this->GlobalResponse('general_error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
