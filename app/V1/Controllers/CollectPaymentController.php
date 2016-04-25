<?php

namespace App\V1\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use DB;

use App\InternalStatusCode;
use App\DataTables\Scopes\InternalStatusCodeScope;
use App\DataTables\CollectPaymentsDataTable;

class CollectPaymentController extends Controller
{
    public function index(CollectPaymentsDataTable $datatable, Request $request)
    {
        $data['code_list']  = InternalStatusCode::select(['internal_status_code', 'status_description'])
                                ->where('is_active', 'y')
                                ->get();
        
        $query_columns  = [
                        'payment_details.payment_details_id',
                        'payment_link_title',
                        'users.first_name',
                        'users.username',
                        'payment_details.total_amount',
                        'customers_name',
                        'customers_contact_number'//,
//                        'settlement_status'
                        ];
        
        $export_columns = [
                        'payment_details_id',
                        'payment_link_title',
                        'first_name',
                        'username',
                        'total_amount',
                        'customers_name',
                        'customers_contact_number'//,
//                        'settlement_status'
                        ];
        $datatable->setQueryColumns($query_columns);
        $datatable->setExportColumns($export_columns);
        
        if($datatable->request()->get('start_date'))
        {
            $column_name    = 'orders.created_at';
            $start_date     = $datatable->request()->get('start_date').' 00:00:00';
            $end_date       = $datatable->request()->get('end_date').' 23:59:59';
            $scope_search   = new CommonDateSearchScope($column_name, $start_date, $end_date);
            $datatable->addScope($scope_search);
        }
        
        return $datatable->render('v1.collect_payments', $data);
    }
    
    public function initiateRefund()
    {
        echo "hi";
    }
}
