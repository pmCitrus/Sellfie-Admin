<?php

namespace App\DataTables\Scopes;

use Yajra\Datatables\Contracts\DataTableScopeContract;
use DB;

class CollectLinkReportedScope implements DataTableScopeContract
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
                ->join('reported', 'reported.parameter_source_id', '=', 'payment_links.payment_links_id')
                ->join(DB::raw("(SELECT COUNT(*) AS report_count, parameter_source_id, reported_customers_mobile, reported_customers_name "
                        . "FROM reported "
                        . "where parameter_source = 'payment_link'"
                        . "GROUP BY parameter_source_id) reported_counter"),
                        "reported_counter.parameter_source_id", "=", "payment_links.payment_links_id")
                ->where('reported.parameter_source', 'payment_link')
                ->where('reported_counter.reported_customers_mobile', '=', 'reported.reported_customers_mobile')
                ->where('reported_counter.reported_customers_name', '=', 'reported.reported_customers_name')
                ->where('reported.is_admin_ignored', '<>', 'y');
    }
}
