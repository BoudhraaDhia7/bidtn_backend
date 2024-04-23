<?php
namespace App\Http\Controllers\Api\Items;

use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Repositories\ProductRepository;
use App\Http\Requests\CreateItemRequest;

class CreateProductController
{   

    use GlobalResponse;
    
    public function __invoke(CreateItemRequest $request): JsonResponse
    {   
        try {
            $validated = $this->getAttributes($request);
        $response = ProductRepository::createProduct(name: $validated['name'], category: $validated['category'], description: $validated['description'], imageArray: [$validated['images']]);
            return $this->GlobalResponse('item_created', Response::HTTP_OK, $response);
        } catch (\Exception $e) {
            Log::error('CreateItemController: Error creating item' . $e->getMessage());
            return $this->GlobalResponse('general_error' ,Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getAttributes(CreateItemRequest $request): array
    {
        return $request->validated();
    }
}
