<?php

namespace App\V1\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use DB;
use Sentinel;

use App\DataTables\OrdersDataTable;
use App\InternalStatusCode;
use App\DataTables\Scopes\InternalStatusCodeScope;

use App\Services\CitrusRefund;
use Log;

class OrderController extends Controller
{
    public function __construct(CitrusRefund $refund_service) {
        $this->refund_service   = $refund_service;
    }
    
    public function index(OrdersDataTable $datatable, Request $request)
    {
        $data['code_list']  = InternalStatusCode::select(['internal_status_code', 'status_description'])
                                ->where('is_active', 'y')
                                ->get();
        
        $query_columns  = [
                        'orders.orders_id',
                        'product_name',
                        'users.first_name',
                        'users.username',
                        'orders.total_amount',
                        'customers_name',
                        'customers_contact_number',
                        'orders.internal_status_code',
                        'status_description'
                        ];
        
        $export_columns = [
                        'orders_id',
                        'product_name',
                        'first_name',
                        'username',
                        'total_amount',
                        'customers_name',
                        'customers_contact_number',
                        'status_description'
                        ];
        $datatable->setQueryColumns($query_columns);
        $datatable->setExportColumns($export_columns);
        
        if(Request::segment(2) && Request::segment(2) != 'all')
        {
            $status_code_scope  = new InternalStatusCodeScope('orders', Request::segment(2));
            $datatable->addScope($status_code_scope);
        }
        
        if($datatable->request()->get('start_date'))
        {
            $column_name    = 'orders.created_at';
            $start_date     = $datatable->request()->get('start_date').' 00:00:00';
            $end_date       = $datatable->request()->get('end_date').' 23:59:59';
            $scope_search   = new CommonDateSearchScope($column_name, $start_date, $end_date);
            $datatable->addScope($scope_search);
        }
        
        return $datatable->render('v1.orders', $data);
    }
    
    public function refund()
    {
        
        $user_data['TxId']                  = '100000185';
        $user_data['pgTxnNo']               = '1520035311360551';
        $user_data['issuerRefNo']           = '605543031508';
        $user_data['authIdCode']            = '999999';
        $user_data['currency']              = 'INR';
        $user_data['amount']                = '350.75';
        
        $user_data['refund_status']         = config('status_codes.order_status_codes.RefundInitiated');
        $user_data['refund_type']           = Request::segment(4);
        $user_data['refund_source']         = 'admin_portal';
        $user_data['refund_initiated_by']   = Sentinel::getUser()->email;
        $user_data['created_at']            = date('Y-m-d H:i:s');
        $user_data['updated_at']            = date('Y-m-d H:i:s');
        
        $inserted  = DB::table('refunds')->insert($user_data);
        if($inserted)
        {
//            $refunds_id = DB::table('refunds')
//            $insert_data['refunds_id']          = $refunds_id;
//            $insert_data['internal_status_code']= $user_data['refund_status'];
//            $insert_data['created_at']          = date('Y-m-d H:i:s');
//            $insert_data['updated_at']          = date('Y-m-d H:i:s');
//            DB::table('refunds_history')->insert($insert_data);
        }
//        $this->refund_service->refund($user_data);
    }
}
