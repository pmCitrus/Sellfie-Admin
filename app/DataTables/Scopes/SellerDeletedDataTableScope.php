<?php

namespace App\DataTables\Scopes;

use Yajra\Datatables\Contracts\DataTableScopeContract;

class SellerDeletedDataTableScope implements DataTableScopeContract
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
                 ->leftJoin('user_kycs', 'user_kycs.users_id', '=', 'users.users_id')
                 ->where('block_status', 'n')
                 ->onlyTrashed();
    }
}
