<?php

namespace App\DataTables\Scopes;

use Yajra\Datatables\Contracts\DataTableScopeContract;

class CommonDateSearchScope implements DataTableScopeContract
{
    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    
    protected $column_name;
    protected $start_date;
    protected $end_date;
    
    public function __construct($column_name, $start_date, $end_date)
    {
        $this->column_name  = $column_name;
        $this->start_date   = $start_date;
        $this->end_date     = $end_date;
    }
            
    public function apply($query)
    {
         return $query
                 ->whereBetween($this->column_name, [$this->start_date, $this->end_date]);
    }
}
