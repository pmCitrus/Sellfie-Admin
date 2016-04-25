<?php

namespace App\DataTables;

use Yajra\Datatables\Services\DataTable;
use DB;
use Log;

//Eloquent
use App\User;

class UsersDataTable extends DataTable
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
        $users  = User::join('department_user_mappings', 'department_user_mappings.users_id', '=', 'users.users_id')
                    ->join('departments', 'departments.departments_id', '=', 'department_user_mappings.departments_id')
                    ->join('role_users', 'role_users.user_id', '=', 'users.users_id')
                    ->join('roles', 'roles.id', '=', 'role_users.role_id')
                    ->select($this->query_columns)
                    ->where('roles.slug', '<>', 'sellfie-users')
//                    ->where('block_status', 'n')
//                    ->withTrashed()
                    ->orderBy('users.created_at', 'desc');
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
                    ->ajax('url("users")')
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
        return 'users';
    }
}
