<?php

namespace App\Http\Controllers\Api\Auction;

use App\Helpers\QueryConfig;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\GlobalResponse;
use App\Traits\PaginationParams;
use Illuminate\Http\JsonResponse;
use App\Repositories\AuctionRepository;

class GetAuctionsController
{
    use GlobalResponse;
    use PaginationParams;

    public function __invoke(Request $request): JsonResponse
    {  
        try {
            $params = $this->getAttributes($request);
            $auctions = AuctionRepository::index($params);
            return $this->GlobalResponse('auctions_retrieved', Response::HTTP_OK, $auctions, $params->getPaginated());
        } catch (\Exception $e) {
            \Log::error('GetLiveAuctionController: Error retrieving auctions' . $e->getMessage());
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
