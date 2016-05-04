<?php

namespace App\DataTables\Scopes;

use Yajra\Datatables\Contracts\DataTableScopeContract;

class TransactionStatusCodeScope implements DataTableScopeContract
{
    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    protected $pg_status_code;
    
    public function __construct($pg_status_code)
    {
        $this->pg_status_code   = $pg_status_code;
    }
    
    public function apply($query)
    {
         return $query
                 ->where('pg_status_codes.pg_status_code', $this->pg_status_code);
    }
}
