<?php

namespace App\DataTables\Scopes;

use Yajra\Datatables\Contracts\DataTableScopeContract;
use DB;

class ProductReportedScope implements DataTableScopeContract
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
                ->join('reported', 'reported.parameter_source_id', '=', 'products.products_id')
                ->join(DB::raw("(SELECT COUNT(*) AS report_count, parameter_source_id, reported_customers_mobile, reported_customers_name "
                        . "FROM reported "
                        . "where parameter_source = 'product'"
                        . "GROUP BY parameter_source_id) reported_counter"),
                        "reported_counter.parameter_source_id", "=", "products.products_id")
                ->where('reported.parameter_source', 'product')
                ->where('reported_counter.reported_customers_mobile', '=', 'reported.reported_customers_mobile')
                ->where('reported_counter.reported_customers_name', '=', 'reported.reported_customers_name')
                ->where('reported.is_admin_ignored', '<>', 'y');
    }
}
