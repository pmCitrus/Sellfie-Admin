<?php

namespace App\DataTables;

use Yajra\Datatables\Services\DataTable;

//Eloquent
use App\Order;

class OrdersDataTable extends DataTable
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
        $orders = Order::join('users', 'users.users_id', '=', 'orders.seller_user_id')
                    ->join('order_items', 'order_items.orders_id', '=', 'orders.orders_id')
                    ->join('products', 'products.products_id', '=', 'order_items.products_id')
                    ->join('payment_details', 'payment_details.payment_ref_id', '=', 'orders.payment_ref_id')
                    ->join('internal_status_codes', 'internal_status_codes.internal_status_code', '=', 'orders.internal_status_code')
                    ->select($this->query_columns)
                    ->orderBy('orders.updated_at', 'desc')
                    ->orderBy('orders.orders_id', 'desc');
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
                    ->ajax('url("orders")')
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
        return 'orders';
    }
}
