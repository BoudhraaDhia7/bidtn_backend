<?php

namespace App\Http\Controllers\Api\Product;
use App\Helpers\AuthHelper;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use App\Traits\GlobalResponse;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Repositories\ProductRepository;
use App\Http\Requests\StoreProductRequest;

class CreateProductController
{   

    use GlobalResponse;
    
    /**
     * Store a new product in the database.
     *
     * @param StoreProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Post(
        path: "/api/products/create",
        tags: ["Products"],
        summary: "Create a new product",
        description: "Creates a new product with name, description, categories, and images. Requires multipart/form-data for image upload.",
        requestBody: new OA\RequestBody(
            description: "Product data and images",
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: "multipart/form-data",
                    schema: new OA\Schema(
                        type: "object",
                        required: ["name", "description", "categories", "images"],
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
                                property: "categories",
                                type: "array",
                                description: "List of category IDs for the product",
                                items: new OA\Items(
                                    type: "integer"
                                )
                            ),
                            new OA\Property(
                                property: "images",
                                type: "array",
                                description: "Array of images for the product",
                                items: new OA\Items(
                                    type: "string",
                                    format: "binary"
                                )
                            )
                        ]
                    )
                )
            ]
        ),
        responses: [
            new OA\Response(
                response: "200",
                description: "Product created successfully",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "product",
                                type: "object",
                                description: "Details of the newly created product",
                                properties: [
                                    new OA\Property(
                                        property: "id",
                                        type: "integer",
                                        description: "ID of the product"
                                    ),
                                    new OA\Property(
                                        property: "name",
                                        type: "string",
                                        description: "Name of the product"
                                    ),
                                    new OA\Property(
                                        property: "description",
                                        type: "string",
                                        description: "Description of the product"
                                    )
                                ]
                            ),
                            new OA\Property(
                                property: "images",
                                type: "array",
                                description: "List of image URLs for the product",
                                items: new OA\Items(
                                    type: "string"
                                )
                            )
                        ]
                    )
                )
            ),
            new OA\Response(
                response: "400",
                description: "Bad Request - Missing Required Fields",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "error",
                                type: "string",
                                description: "Specific error message such as 'product_image_required' or 'product_category_required'"
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
                                description: "General server error message"
                            )
                        ]
                    )
                )
            )
        ]
    )]

    
    public function __invoke(StoreProductRequest $request): JsonResponse
    {   
        try {
            $user = AuthHelper::currentUser();
            $validated = $this->getAttributes($request);
            $response = ProductRepository::storeProduct($validated['name'], $validated['description'], $validated['categories'], $validated['files'],$user);
            return $this->GlobalResponse('product_created', Response::HTTP_OK, $response);
        } catch (\Exception $e) {
            Log::error('CreateProductController: Error creating product' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode($e->getCode()));
        }
    }

    private function getAttributes(StoreProductRequest $request): array
    {
        return $request->validated();
    }
}
