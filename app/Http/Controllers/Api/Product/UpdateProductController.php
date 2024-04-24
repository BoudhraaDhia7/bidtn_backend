<?php
namespace App\Http\Controllers\Api\Product;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
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
