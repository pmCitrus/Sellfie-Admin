<?php

namespace App\DataTables;

use Yajra\Datatables\Services\DataTable;
use Carbon\Carbon;

//Eloquent
use App\User;
use DB;
use Log;

class SellersDataTable extends DataTable
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
        $max_date   = Carbon::today()->modify('-3 months')->toDateTimeString();
        $users      = User::join('user_profiles', 'user_profiles.users_id', '=', 'users.users_id')
                        ->leftJoin('user_device_details', 'user_device_details.users_id', '=', 'users.users_id')
                        ->join('role_users', 'role_users.user_id', '=', 'users.users_id')
                        ->join('roles', 'roles.id', '=', 'role_users.role_id')
                        ->select($this->query_columns)
                        ->where('roles.slug', '=', 'sellfie-users')
                        ->where('users.created_at', '>=', $max_date)
                        ->orderBy('user_profiles.updated_at', 'desc')
                        ->orderBy('user_profiles.users_id', 'desc');
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
                    ->ajax('url("sellers")')
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
        return 'sellers';
    }
}
