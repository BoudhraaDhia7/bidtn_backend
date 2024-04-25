<?php

namespace App\Http\Controllers\Api\Product;

use App\Helpers\AuthHelper;
use App\Helpers\QueryConfig;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use App\Traits\GlobalResponse;
use App\Helpers\ResponseHelper;
use App\Traits\PaginationParams;
use Illuminate\Http\JsonResponse;
use App\Repositories\ProductRepository;

class GetProductsController
{
    use GlobalResponse;
    use PaginationParams;
    
    #[OA\Get(
        path: "/api/products",
        tags: ["Products"],
        summary: "Get list of products",
        description: "Retrieves a list of products with optional filters, sorting, and pagination. Admins can view all products, while other users can only view their own products.",
        parameters: [
            new OA\Parameter(
                name: "filters",
                in: "query",
                description: "Filter conditions for fetching products, such as category, price range, etc.",
                required: false,
                schema: new OA\Schema(
                    type: "string"
                )
            ),
            new OA\Parameter(
                name: "order_by",
                in: "query",
                description: "Field to order the products by",
                required: false,
                schema: new OA\Schema(
                    type: "string"
                )
            ),
            new OA\Parameter(
                name: "direction",
                in: "query",
                description: "Direction of the sort order (asc or desc)",
                required: false,
                schema: new OA\Schema(
                    type: "string",
                    enum: ["asc", "desc"],
                    default: "asc"
                )
            ),
            new OA\Parameter(
                name: "page",
                in: "query",
                description: "Page number for pagination",
                required: false,
                schema: new OA\Schema(
                    type: "integer",
                    format: "int32"
                )
            ),
            new OA\Parameter(
                name: "per_page",
                in: "query",
                description: "Number of products per page",
                required: false,
                schema: new OA\Schema(
                    type: "integer",
                    format: "int32",
                    default: 10
                )
            )
        ],
        responses: [
            new OA\Response(
                response: "200",
                description: "List of products retrieved successfully",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "data",
                                type: "array",
                                description: "An array of products",
                                items: new OA\Items(
                                    type: "object",
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
                                            description: "Media associated with the product",
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
                                            description: "Categories of the product",
                                            items: new OA\Items(
                                                type: "object",
                                                properties: [
                                                    new OA\Property(
                                                        property: "name",
                                                        type: "string",
                                                        description: "Name of the category"
                                                    )
                                                ]
                                            )
                                        )
                                    ]
                                )
                            )
                        ]
                    )
                )
            ),
            new OA\Response(
                response: "401",
                description: "Unauthorized",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "error",
                                type: "string",
                                description: "Error message explaining unauthorized access"
                            )
                        ],
                        example: [
                            "error"=> "Unauthorized access",
                            ]
                    )
                )
            )
        ]
    )]
    
    public function __invoke(Request $request): JsonResponse
    {    
          
        try {
            $user = AuthHelper::currentUser();
            $params = $this->getAttributes($request);
            $products = ProductRepository::GetProducts($params, $user);    
            return $this->GlobalResponse('products_retrieved', Response::HTTP_OK, $products , true);
        } catch (\Exception $e) {
            \Log::error('GetProductsController: Error retrieving products' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode($e->getCode()));
        }
    }

    /**
     * @param Request $request
     * @return QueryConfig
     */
    private function getAttributes(Request $request): QueryConfig
    {
        $paginationParams = $this->getPaginationParams($request);

        $filters = [
            'name' => $request->input('name'),
            'category' => $request->input('category'),
            'status' => $request->input('status'),
            'is_confirmed' => $request->input('is_confirmed'),
        ];

        $search = new QueryConfig();
        $search
            ->setFilters($filters)
            ->setPerPage($paginationParams['PER_PAGE'])
            ->setOrderBy($paginationParams['ORDER_BY'])
            ->setDirection($paginationParams['DIRECTION'])
            ->setPaginated($paginationParams['PAGINATION']);
        return $search;
    }
}
