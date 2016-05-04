<?php

namespace App\V1\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use DB;

use App\DataTables\PaymentDetailsDataTable;
use App\DataTables\Scopes\TransactionStatusCodeScope;

use App\TransactionStatusCode;

class TransactionController extends Controller
{
    public function index(PaymentDetailsDataTable $datatable)
    {
        $data['code_list']  = TransactionStatusCode::select(['pg_status_code', 'pg_status_description'])
                                ->where('is_active', 'y')
                                ->get();
        
        $query_columns      = [
                                'payment_details.payment_ref_id',
                                'pg_status_description',
                                'payment_details.created_at',
                                'payment_source',
                                'payment_source_id',
                                'providers_name',
                                'payment_details.total_amount',
                                'users.first_name',
                                'users.username'
                                ];
        
        $export_columns     = [
                                'created_at',
                                'payment_ref_id',
                                'pg_status_description',
                                'payment_source',
                                'payment_source_id',
                                'providers_name',
                                'total_amount',
                                'first_name',
                                'username'
                                ];
        $datatable->setQueryColumns($query_columns);
        $datatable->setExportColumns($export_columns);
        
        if(Request::segment(2) && Request::segment(2) != 'all')
        {
            $status_code_scope  = new TransactionStatusCodeScope(Request::segment(2));
            $datatable->addScope($status_code_scope);
        }
        
        if($datatable->request()->get('start_date'))
        {
            $column_name    = 'payment_details.created_at';
            $start_date     = $datatable->request()->get('start_date').' 00:00:00';
            $end_date       = $datatable->request()->get('end_date').' 23:59:59';
            $scope_search   = new CommonDateSearchScope($column_name, $start_date, $end_date);
            $datatable->addScope($scope_search);
        }
        
        return $datatable->render('v1.transactions', $data);
    }
    
    public function show($id)
    {
        echo $id;
    }
}
