<?php

namespace App\DataTables\Scopes;

use Yajra\Datatables\Contracts\DataTableScopeContract;

class SellerActiveDataTableScope implements DataTableScopeContract
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
                 ->join('user_kycs', 'user_kycs.users_id', '=', 'users.users_id')
                 ->whereNotNull('profile_name');
    }
}
