<?php

namespace App\DataTables\Scopes;

use Yajra\Datatables\Contracts\DataTableScopeContract;
use DB;

class SellerSignedUpDataTableScope implements DataTableScopeContract
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
                 ->leftJoin(DB::raw("(SELECT COUNT(*) AS products_bought, buyer_user_id FROM orders "
                            . "where is_order_successful='y' "
                            . "GROUP BY buyer_user_id) products"),
                            "products.buyer_user_id", "=", "users.users_id")
                 ->whereNull('profile_name');
    }
}
