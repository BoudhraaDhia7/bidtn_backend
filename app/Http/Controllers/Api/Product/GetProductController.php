<?php

namespace App\Http\Controllers\Api\Product;

use App\Helpers\AuthHelper;
use OpenApi\Attributes as OA;
use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
use App\Helpers\ResponseHelper;
use App\Traits\PaginationParams;
use Illuminate\Http\JsonResponse;
use App\Exceptions\GlobalException;
use App\Repositories\ProductRepository;

class GetProductController
{
    use GlobalResponse;
    use PaginationParams;

    /**
     * Get a product from the database.
     * @param $id
     * @return JsonResponse
     */

     #[OA\Get(
        path: "/api/products/{id}",
        tags: ["Products"],
        summary: "Get a product",
        description: "Retrieves detailed information about a product, including associated media and categories. Requires user authentication and that the user either owns the product or is an administrator.",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "The ID of the product to retrieve",
                schema: new OA\Schema(
                    type: "integer"
                )
            )
        ],
        responses: [
            new OA\Response(
                response: "200",
                description: "Product retrieved successfully",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "product",
                                type: "object",
                                description: "The product details",
                                properties: [
                                    new OA\Property(
                                        property: "name",
                                        type: "string",
                                        description: "Name of the product"
                                    ),
                                    new OA\Property(
                                        property: "description",
                                        type: "string",
                                        description: "Description of the product"
                                    ),
                                    new OA\Property(
                                        property: "media",
                                        type: "array",
                                        description: "List of media associated with the product",
                                        items: new OA\Items(
                                            type: "object",
                                            properties: [
                                                new OA\Property(
                                                    property: "url",
                                                    type: "string",
                                                    description: "URL of the media item"
                                                )
                                            ]
                                        )
                                    ),
                                    new OA\Property(
                                        property: "categories",
                                        type: "array",
                                        description: "List of categories associated with the product",
                                        items: new OA\Items(
                                            type: "object",
                                            properties: [
                                                new OA\Property(
                                                    property: "name",
                                                    type: "string",
                                                    description: "Name of the category"
                                                ),
                                                new OA\Property(
                                                    property: "media",
                                                    type: "array",
                                                    description: "Media associated with the category",
                                                    items: new OA\Items(
                                                        type: "object",
                                                        properties: [
                                                            new OA\Property(
                                                                property: "url",
                                                                type: "string",
                                                                description: "URL of the media item"
                                                            )
                                                        ]
                                                    )
                                                )
                                            ]
                                        )
                                    )
                                ]
                            )
                        ]
                    )
                )
            ),
            new OA\Response(
                response: "401",
                description: "Unauthorized to view the product",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "error",
                                type: "string",
                                description: "Error message indicating unauthorized access to view the product"
                            )
                        ],
                        example: [
                            "error" => "product_unauthorized_view"
                        ]
                    )
                )
            ),
            new OA\Response(
                response: "404",
                description: "Product not found",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "error",
                                type: "string",
                                description: "Error message indicating the product was not found"
                            )
                        ],
                        example: [
                            "error" => "product_not_found"
                        ]
                    )
                )
            )
        ]
    )]

    public function __invoke($id): JsonResponse
    {
        try {
            $user = AuthHelper::currentUser();
            $product = ProductRepository::GetProduct($id, $user);
            return $this->GlobalResponse('products_retrieved', Response::HTTP_OK, $product);
        } catch (GlobalException $e) {
            \Log::error('GetProductController: Error retrieving products' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode($e->getCode()));
        }
    }
}
