<?php

namespace App\Http\Controllers\Api\Categories;

use OpenApi\Attributes as OA;
use App\Traits\GlobalResponse;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

use App\Repositories\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;

class GetCategoriesController extends Controller
{
    use GlobalResponse;

    /**
     * Get all categories from the database.
     * @return JsonResponse
     */
    #[OA\Get(
        path: "/api/categories",
        tags: ["Categories"],
        summary: "Get all categories",
        description: "Retrieves a list of all categories available in the system. This endpoint does not require any parameters and is accessible without authentication.",
        responses: [
            new OA\Response(
                response: "200",
                description: "List of all categories retrieved successfully",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "array",
                        items: new OA\Items(
                            type: "object",
                            properties: [
                                new OA\Property(
                                    property: "id",
                                    type: "integer",
                                    description: "The unique identifier for the category"
                                ),
                                new OA\Property(
                                    property: "name",
                                    type: "string",
                                    description: "The name of the category"
                                ),
                                new OA\Property(
                                    property: "description",
                                    type: "string",
                                    description: "A brief description of the category"
                                )
                            ]
                        ),
                        example: [
                            [
                                "id"=> 1,
                                "name"=> "Electronics",
                                "description"=> "Devices, gadgets, and electronics"
                            ],
                            [
                                "id"=> 2,
                                "name"=> "Apparel",
                                "description"=> "Clothing and accessories for all genders and ages"
                            ]
                        ]
                    )
                )
            )
        ]
    )]
    
    public function __invoke(): JsonResponse
    {
        try {
            $response = CategoryRepository::index();
            return $this->GlobalResponse('category_retrived', Response::HTTP_OK, $response);
        } catch (\Exception $e) {
            Log::error('GetCategoriesController: Error registering user' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode($e->getCode()));
        }
    }
}
