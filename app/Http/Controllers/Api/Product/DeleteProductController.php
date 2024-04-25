<?php
namespace App\Http\Controllers\Api\Product;

use App\Helpers\AuthHelper;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use App\Traits\GlobalResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Repositories\ProductRepository;

class DeleteProductController
{   

    use GlobalResponse;
    
    /* Delete a product from the database.
     * @param $id
     * @return JsonResponse
     */
     #[OA\Delete(
        path: "/api/products/{id}",
        tags: ["Products"],
        summary: "Delete a product",
        description: "Deletes a specified product by its ID. Requires user authentication and that the user owns the product or is an administrator.",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "The ID of the product to delete",
                schema: new OA\Schema(
                    type: "integer"
                )
            )
        ],
        responses: [
            new OA\Response(
                response: "200",
                description: "Product deleted successfully",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "success",
                                type: "boolean",
                                description: "True if the product was successfully deleted"
                            )
                        ],
                        example: [
                            "success" => true
                        ]
                    )
                )
            ),
            new OA\Response(
                response: "302",
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
                            "success" => true
                        ]
                    )
                )
            ),
            new OA\Response(
                response: "401",
                description: "Unauthorized access",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "error",
                                type: "string",
                                description: "Error message indicating unauthorized access to delete the product"
                            )
                        ],
                        example: [
                            "success" => true
                        ]
                    )
                )
            ),
            new OA\Response(
                response: "500",
                description: "Failed to delete product",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "error",
                                type: "string",
                                description: "Error message indicating failure to delete the product"
                            )
                        ],
                        example: [
                            "success" => true
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
            $response = ProductRepository::deleteProduct($id, $user);
            return $this->GlobalResponse('product_deleted', Response::HTTP_OK, $response);
        } catch (\Exception $e) {
            Log::error('DeleteProductController: Error creating product' . $e->getMessage());
            return $this->GlobalResponse('general_error' ,Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
