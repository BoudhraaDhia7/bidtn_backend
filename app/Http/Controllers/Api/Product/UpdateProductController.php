<?php
namespace App\Http\Controllers\Api\Product;

use App\Helpers\AuthHelper;
use OpenApi\Attributes as OA;
use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Repositories\ProductRepository;
use App\Http\Requests\UpdateProductRequest;

class UpdateProductController
{
    use GlobalResponse;

    /**
     * Update a product in the database.
     *
     * @param UpdateProductRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    #[OA\Put(
        path: "/api/products/{id}",
        tags: ["Products"],
        summary: "Update a product",
        description: "Updates an existing product's details and manages associated images. Allows adding new images and deleting existing ones. Requires user authentication and that the user owns the product or is an administrator.",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "The ID of the product to update",
                schema: new OA\Schema(
                    type: "integer"
                )
            )
        ],
        requestBody: new OA\RequestBody(
            description: "Product details and images to update",
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: "multipart/form-data",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "name",
                                type: "string",
                                description: "Updated name of the product"
                            ),
                            new OA\Property(
                                property: "category",
                                type: "string",
                                description: "Updated category of the product"
                            ),
                            new OA\Property(
                                property: "description",
                                type: "string",
                                description: "Updated description of the product"
                            ),
                            new OA\Property(
                                property: "newImages",
                                type: "array",
                                description: "Array of new images for the product",
                                items: new OA\Items(
                                    type: "string",
                                    format: "binary"
                                )
                            ),
                            new OA\Property(
                                property: "deletedImages",
                                type: "array",
                                description: "Array of IDs for images to be deleted",
                                items: new OA\Items(
                                    type: "integer"
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
                description: "Product updated successfully",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "success",
                                type: "boolean",
                                description: "True if the product was successfully updated"
                            ),
                            new OA\Property(
                                property: "message",
                                type: "string",
                                description: "Success message confirming the product was updated"
                            )
                        ],
                        example: [
                            "success"=> true,
                            "message"=> "Product updated successfully"
                        ]
                    )
                )
            ),
            new OA\Response(
                response: "401",
                description: "Unauthorized to update the product",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "error",
                                type: "string",
                                description: "Error message indicating unauthorized access to update the product"
                            )
                        ],
                        example: [
                            "error"=> "product_unauthorized"
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
                            "error"=> "product_not_found"
                            ]
                    )
                )
            )
        ]
    )]

    
    public function __invoke(UpdateProductRequest $request, $id): JsonResponse
    {
        try {
            $user = AuthHelper::currentUser();
            $validated = $this->getAttributes($request);
            $imageToStoreArray = isset($validated['files']) ? $validated['files'] : [];
            $imageToDeleteArray = isset($validated['deletedMedia']) ? $validated['deletedMedia'] : [];
            $response = ProductRepository::updateProduct($id, $validated['name'], $validated['categories'], $validated['description'], $imageToStoreArray, $imageToDeleteArray, $user);
            return $this->GlobalResponse('product_updated', Response::HTTP_OK, $response);
        } catch (\Exception $e) {
            Log::error('UpdateController: Error creating Product' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode($e->getCode()));
        }
    }

    private function getAttributes(UpdateProductRequest $request): array
    {
        return $request->validated();
    }
}
