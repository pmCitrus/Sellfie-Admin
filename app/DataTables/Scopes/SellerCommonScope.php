<?php

namespace App\DataTables\Scopes;

use Yajra\Datatables\Contracts\DataTableScopeContract;
use DB;

class SellerCommonScope implements DataTableScopeContract
{
    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
         return $query
                    ->leftJoin(DB::raw("(SELECT COUNT(*) AS products_created, users_id FROM products "
                            . "GROUP BY users_id) products"),
                            "products.users_id", "=", "users.users_id")
                    ->leftJoin(DB::raw("(SELECT COUNT(*) AS collect_links_created, users_id FROM payment_links "
                            . "GROUP BY users_id) payment_links"),
                            "payment_links.users_id", "=", "users.users_id");
    }
}
