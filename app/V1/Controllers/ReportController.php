<?php

namespace App\V1\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

use App\DataTables\ReportsDataTable;
use App\DataTables\Scopes\CommonDateSearchScope;

use App\User;
class ReportController extends Controller
{
    public function index(ReportsDataTable $datatable)
    {
        $max_date       = Carbon::today()->modify('-3 months')->toDateTimeString();
        $file_name      = 'users';
        $export_columns = [
                        'users_id',
                        'username'
                        ];
        $dynamic_query  = User::select(['users_id', 'username'])
                            ->where('users.created_at', '>=', $max_date);
        $datatable->setFileName($file_name);
        $datatable->setQuery($dynamic_query);
        $datatable->setExportColumns($export_columns);
        
        if($datatable->request()->get('start_date'))
        {
            $column_name    = 'users.created_at';
            $start_date     = $datatable->request()->get('start_date').' 00:00:00';
            $end_date       = $datatable->request()->get('end_date').' 23:59:59';
            $scope_search   = new CommonDateSearchScope($column_name, $start_date, $end_date);
            $datatable->addScope($scope_search);
        }
        
        return $datatable->render('v1.reports');
    }
}
