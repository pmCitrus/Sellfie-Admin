<?php

namespace App\DataTables;

use Yajra\Datatables\Services\DataTable;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

//Eloquent
use App\UserKyc;

class UserKycDataTable extends DataTable
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
        $users      = UserKyc::join('users', 'users.users_id', '=', 'user_kycs.users_id')
                        ->join('user_profiles', 'user_profiles.users_id', '=', 'users.users_id')
                        ->select($this->query_columns)
                        ->where('user_kycs.created_at', '>=', $max_date)
                        ->orderBy('user_kycs.updated_at', 'desc')
                        ->orderBy('users.users_id', 'desc');
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
                    ->ajax('url("kyc_new")')
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
        return 'kyc_new';
    }
}
