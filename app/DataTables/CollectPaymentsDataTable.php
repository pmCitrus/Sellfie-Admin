<?php

namespace App\DataTables;

use Yajra\Datatables\Services\DataTable;
use DB;
use Log;

//Eloquent
use App\PaymentDetail;

class CollectPaymentsDataTable extends DataTable
{
    protected $query_columns;
    protected $export_columns;
    
    public function setQueryColumns($query_columns)
    {
        $this->query_columns    = $query_columns;
    }
    
    public function setExportColumns($export_columns)
    {
        $this->export_columns   = $export_columns;
    }
    
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $data   = $this->datatables
                    ->eloquent($this->query())
                    ->addColumn('action', '')
                    ->make(true);
        return $data;
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $orders = PaymentDetail::join('users', 'users.users_id', '=', 'payment_details.seller_user_id')
                    ->join('payment_links', 'payment_links.payment_links_id', '=', 'payment_details.payment_source_id')
                    ->select($this->query_columns);
        return $this->applyScopes($orders);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->ajax('url("collect_payments")')
                    ->addAction(['width' => '80px'])
                    ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return $this->export_columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'collect_payments';
    }
}
