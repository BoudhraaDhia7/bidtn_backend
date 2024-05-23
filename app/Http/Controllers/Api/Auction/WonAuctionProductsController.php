<?php

namespace App\Http\Controllers\Api\Auction;

use App\Helpers\QueryConfig;
use App\Http\Controllers\Controller;
use App\Repositories\AuctionRepository;
use App\Traits\GlobalResponse;
use App\Traits\PaginationParams;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WonAuctionProductsController extends Controller
{
    use GlobalResponse;
    use PaginationParams;

    /**
     * Get all auctions from the database.
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = auth()->user();

        $queryConfig = $this->getAttributes($request);

        try {
            $response = AuctionRepository::wonProducts($user, $queryConfig);
            return $this->GlobalResponse('winned_auction_product_retrieved', Response::HTTP_OK, $response , true);
        } catch (\Exception $e) {
            return $this->GlobalResponse('WonAuctionProducts', Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }
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
