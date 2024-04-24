<?php

namespace App\Http\Controllers\Api\Items;

use App\Helpers\QueryConfig;
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

    public function __invoke(Request $request): JsonResponse
    {    
            $user = auth()->user();
            $params = $this->getAttributes($request);
            $items = ProductRepository::GetProducts($params, $user->id);
            return $this->GlobalResponse('items_retrieved', Response::HTTP_OK, $items , true);
        try {
            
        } catch (\Exception $e) {
            \Log::error('GetItemsController: Error retrieving items' . $e->getMessage());
            return $this->GlobalResponse('general_error', Response::HTTP_INTERNAL_SERVER_ERROR);
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
