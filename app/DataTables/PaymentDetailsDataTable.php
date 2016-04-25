<?php

namespace App\DataTables;

use Yajra\Datatables\Services\DataTable;
use DB;
use Log;

//Eloquent
use App\PaymentDetail;

class PaymentDetailsDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $data   = $this->datatables
                    ->eloquent($this->query())
                    ->addColumn('action', 'View')
                    ->make(true);
        Log::info($data);
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
                    ->join('providers', 'providers.providers_id', '=', 'payment_details.shares_providers_id')
                    ->select([
                        'payment_details.payment_details_id',
                        'payment_details.created_at',
                        'payment_source',
                        'payment_source_id',
                        'providers_name',
                        'payment_details.total_amount',
                        'users.first_name',
                        'users.username'
                        ]);
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
                    ->ajax('')
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
        return [
        ];
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
