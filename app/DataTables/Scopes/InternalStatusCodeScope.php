<?php

namespace App\DataTables\Scopes;

use Yajra\Datatables\Contracts\DataTableScopeContract;

class InternalStatusCodeScope implements DataTableScopeContract
{
    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    protected $table_name;
    protected $internal_status_code;
    
    public function __construct($table_name, $internal_status_code)
    {
        $this->table_name           = $table_name;
        $this->internal_status_code = $internal_status_code;
    }
    
    public function apply($query)
    {
         return $query
                 ->where($this->table_name.'.internal_status_code', $this->internal_status_code);
    }
}
