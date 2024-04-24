<?php
namespace App\Http\Controllers\Api\Product;

use App\Helpers\AuthHelper;
use Illuminate\Http\Response;
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
