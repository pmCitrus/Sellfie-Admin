<?php

namespace App\DataTables\Scopes;

use Yajra\Datatables\Contracts\DataTableScopeContract;

class KycRejectedScope implements DataTableScopeContract
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
                 ->join('users as admin_users', 'admin_users.users_id', '=', 'user_kycs.action_done_by')
                 ->where('user_kycs.is_admin_approved', 'n');
    }
}
