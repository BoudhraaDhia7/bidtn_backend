<?php

namespace App\Http\Controllers\Api\Product;

use App\Helpers\AuthHelper;
use App\Helpers\QueryConfig;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
use App\Traits\PaginationParams;
use Illuminate\Http\JsonResponse;
use App\Repositories\ProductRepository;

class GetProductsController
{
    use GlobalResponse;
    use PaginationParams;

    /* Get a list of products from the database.
     * @param Request $request
     * @return JsonResponse
     */
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
