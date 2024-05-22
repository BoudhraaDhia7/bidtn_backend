<?php

namespace App\Http\Controllers\Api\Auction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Traits\PaginationParams;
use App\Helpers\ResponseHelper;
use App\Helpers\QueryConfig;
use App\Helpers\AuthHelper;

use Illuminate\Http\JsonResponse;
use App\Traits\GlobalResponse;
use Illuminate\Http\Response;

use App\Repositories\AuctionRepository;
use Illuminate\Support\Facades\Log;


class ListAuctionActivityController extends Controller
{
    use GlobalResponse;
    use PaginationParams;

    /**
     *
     * Get all auctions activity from the database.
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $user = AuthHelper::currentUser();
            $params = $this->getAttributes($request);
            $auctions = AuctionRepository::auctionActivity($params, $user);
            return $this->GlobalResponse('auctions_retrieved', Response::HTTP_OK, $auctions, $params->getPaginated());
        } catch (\Exception $e) {
            Log::error('GetLiveAuctionController: Error retrieving auctions' . $e->getMessage());
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
            'category' => $request->input('categories') ?? null,
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
