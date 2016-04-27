<?php

namespace App\DataTables;

use Yajra\Datatables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
//Eloquent
use App\PaymentLink;

class CollectLinksDataTable extends DataTable
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
        $max_date   = Carbon::today()->modify('-3 months')->toDateTimeString();
        $users      = PaymentLink::join('users', 'users.users_id', '=', 'payment_links.users_id')
                        ->join('user_profiles', 'user_profiles.users_id', '=', 'users.users_id')
                        ->select($this->query_columns)
                        ->where('payment_links.created_at', '>=', $max_date)
                        ->orderBy('payment_links.updated_at', 'desc')
                        ->orderBy('payment_links.payment_links_id', 'desc');
        return $this->applyScopes($users);
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
                    ->ajax('url("collect_links")')
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
        return 'collect_links';
    }
}
