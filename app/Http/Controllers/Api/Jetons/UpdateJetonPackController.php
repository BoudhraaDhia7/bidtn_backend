<?php

namespace App\Http\Controllers\Api\Jetons;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseHelper;
use OpenApi\Attributes as OA;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Repositories\JetonRepository;
use App\Http\Requests\UpdateJetonPackRequest;
use App\Models\JetonPack;
use App\Traits\GlobalResponse;
use Symfony\Component\HttpFoundation\Response;

class UpdateJetonPackController extends Controller
{   

    use GlobalResponse;
      /**
     * Create a new jeton pack.
     *
     * This endpoint is responsible for creating a new jeton pack based on the provided details in the request body.
     * It performs validation on the request data and, if successful, creates a new jeton pack and returns a response
     * indicating the success of the operation. This process can result in various responses depending on the outcome
     * of the jeton pack creation attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Post(
        path: "/api/update-jeton-packs/:id",
        tags: ["Jetons"],
        summary: "Create a new jeton pack",
        operationId: "createJetonPack",
        requestBody: new OA\RequestBody(
            description: "Update jeton pack details",
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "name", type: "string", description: "The name of the jeton pack."),
                        new OA\Property(property: "price", type: "number", description: "The price of the jeton pack."),
                        new OA\Property(property: "quantity", type: "integer", description: "The quantity of jetons in the pack."),
                    ],
                    required: ["name", "price", "quantity"]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: Response::HTTP_CREATED,
                description: "Jeton pack updated successfully.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer", description: "The ID of the created jeton pack."),
                        new OA\Property(property: "name", type: "string", description: "The name of the jeton pack."),
                        new OA\Property(property: "price", type: "number", description: "The price of the jeton pack."),
                        new OA\Property(property: "quantity", type: "integer", description: "The quantity of jetons in the pack."),
                    ]
                )
            ),
            new OA\Response(
                response: Response::HTTP_UNPROCESSABLE_ENTITY,
                description: "Unprocessable Entity. Issues with jeton pack details."
            ),
            new OA\Response(
                response: Response::HTTP_INTERNAL_SERVER_ERROR,
                description: "Internal server error or other unspecified error."
            )
        ]
    )]
    public function __invoke(UpdateJetonPackRequest $request, $id): JsonResponse
    {   
        $this->checkAuthrization();
        $jetonPack = JetonPack::findOrfail($id);
        try {
            $validated = $this->getAttributes($request);
            $response = JetonRepository::updateJetonPack($validated['name'], $validated['description'], $validated['price'], $validated['amount'], $jetonPack);
            return $this->GlobalResponse('jeton_pack_updated', Response::HTTP_OK, $response);
        } catch (\Exception $e) {
            Log::error('UpdateJetonPackController: Error update jeton pack'. $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode(500));
        }
    }

    private function getAttributes(UpdateJetonPackRequest $request): array
    {
        return [
            'name' => $request->name,
            'description' => $request->description, 
            'amount' => $request->amount,
            'price' => $request->price,
        ];
    }
    
    private function checkAuthrization()
    {
        $user = auth()->user();

        if ($user->cannot('updateJetonPack' , JetonPack::class)) {
            abort($this->GlobalResponse('failed_to_update_pack', Response::HTTP_UNAUTHORIZED));
        }
    }
}
