<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Helpers\QueryConfig;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\JetonTransaction;
use App\Repositories\JetonTransactionsReposiory;
use App\Traits\GlobalResponse;
use App\Traits\PaginationParams;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ListJetonTransactions extends Controller
{
    use GlobalResponse;
    use PaginationParams;

    public function __invoke(Request $request): JsonResponse
    {   
        try {
            $user = auth()->user();
            $params = $this->getAttributes($request);
            $transactions = JetonTransactionsReposiory::index($params , $user);
            return $this->GlobalResponse('jeton_pack_retrived', Response::HTTP_OK, $transactions, $params->getPaginated());
        } catch (\Exception $e) {
            Log::error('ListJetonPackController: Error listing jeton pack' . $e->getMessage());
            return $this->GlobalResponse($e->getMessage(), ResponseHelper::resolveStatusCode(500));
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
            'filter' => $request->input('filter') ?? null,
            'keyword' => $request->input('keyword') ?? null,
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
