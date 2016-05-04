<?php

namespace App\DataTables;

use Yajra\Datatables\Services\DataTable;
use DB;
use Log;

//Eloquent
use App\PaymentDetail;

class PaymentDetailsDataTable extends DataTable
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
        $transactions   = PaymentDetail::join('users', 'users.users_id', '=', 'payment_details.seller_user_id')
                            ->join('pg_status_codes', 'pg_status_codes.pg_status_code', '=', 'payment_details.pg_status_code')
                            ->join('providers', 'providers.providers_id', '=', 'payment_details.shares_providers_id')
                            ->select($this->query_columns);
        return $this->applyScopes($transactions);
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
                    ->ajax('url["transactions"]')
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
        return 'transactions';
    }
}
