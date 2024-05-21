<?php

namespace App\Repositories;

use App\Helpers\QueryConfig;
use App\Models\JetonTransaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class JetonTransactionsReposiory
{
    /**
     * Get all user jeton transaction from the database.
     *
     * @param QueryConfig $queryConfig
     * @return LengthAwarePaginator|Collection
     */
    public static function index(QueryConfig $queryConfig): LengthAwarePaginator|Collection
    {
        $TransactionsQuery = JetonTransaction::with('user');

        JetonTransaction::applyFilters($queryConfig->getFilters(), $TransactionsQuery);

        $TransactionsQuery->orderBy($queryConfig->getOrderBy(), $queryConfig->getDirection());

        if ($queryConfig->isPaginated()) {
            return $TransactionsQuery->paginate($queryConfig->getPerPage());
        }

        return $TransactionsQuery->get();
    }
}
