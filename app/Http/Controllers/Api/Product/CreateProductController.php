<?php
namespace App\Http\Controllers\Api\Product;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
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
