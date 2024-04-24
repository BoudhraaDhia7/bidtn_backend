<?php

namespace App\Http\Controllers\Api\Product;

use App\Exceptions\GlobalException;
use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;

use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
use App\Traits\PaginationParams;
use Illuminate\Http\JsonResponse;
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
