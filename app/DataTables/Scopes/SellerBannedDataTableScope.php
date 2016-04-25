<?php

namespace App\DataTables\Scopes;

use Yajra\Datatables\Contracts\DataTableScopeContract;
use DB;

class SellerBannedDataTableScope implements DataTableScopeContract
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
                    ->leftJoin(DB::raw("(SELECT COUNT(*) AS orders_completed, seller_user_id FROM orders "
                            . "where finished_at <> '0000-00-00 00:00:00' "
                            . "GROUP BY seller_user_id) orders"),
                            "orders.seller_user_id", "=", "users.users_id")
                    ->join('user_block_details', 'user_block_details.users_id', '=', 'users.users_id')
                    ->join('users as admin_users', 'admin_users.users_id', '=', 'user_block_details.blocked_by')
                    ->where('users.block_status', 'y')
                    ->withTrashed();
    }
}
