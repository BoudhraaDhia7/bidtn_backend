<?php

namespace App\Http\Controllers\Api\User;

use Exception;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use App\Traits\GlobalResponse;
use Illuminate\Http\JsonResponse;
use App\Exceptions\GlobalException;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\BuyTokenRequest;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;

class BuyJetonPackController extends Controller
{   
    use GlobalResponse;
    
    /**
     * Buy Jeton Pack.
     *
     * This endpoint is responsible for buying a jeton pack for the user.
     * It performs validation on the request data and, if successful, processes the purchase.
     * This process can result in various responses depending on the outcome of the purchase attempt.
     *
     * @param Request $request
     * 
     * @return JsonResponse
     */
    #[OA\Post(
        path: "/api/user/buy-jeton-pack",
        tags: ["User"],
        description: "Buy a jeton pack for the user.",
        requestBody: new OA\RequestBody(
            description: "Jeton pack details",
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(property: "pack_id", type: "integer", description: "ID of the jeton pack to buy"),
                        ],
                        required: ["pack_id"]
                    )
                )
            ]
        ),
        responses: [
            new OA\Response(response: 200, description: "Jeton pack purchased successfully"),
            new OA\Response(response: 401, description: "Unauthorized"),
            new OA\Response(response: 422, description: "Unprocessable Entity")
        ]
    )]
    public function __invoke(BuyTokenRequest $request): JsonResponse
    {   
        try {
            $user = auth()->user();  
            $packId = $this->getAttributes($request)['packId'];
            $response = UserRepository::buyJetonPack($packId, $user);         
            return $this->GlobalResponse('jeton_pack_purchased', Response::HTTP_OK , $response);
        } catch (GlobalException $e) {
            Log::error('BuyJetonPackController: Error buying jeton pack: ' . $e->getMessage());
            return $this->GlobalResponse('purchase_error', Response::HTTP_UNAUTHORIZED);
        } catch (Exception $e) {
            Log::error('BuyJetonPackController: Error buying jeton pack: ' . $e->getMessage());
            return $this->GlobalResponse('general_error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getAttributes(BuyTokenRequest $request): array
    {
        return $request->validated();
    }
}
