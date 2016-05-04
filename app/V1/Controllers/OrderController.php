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
use Carbon\Carbon;
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
                        'orders.created_at',
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
                        'created_at',
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
    
    public function show()
    {
        $orders_id      = Request::segment(3);
        
        $order_data     = DB::table('orders')
                            ->join('internal_status_codes', 'internal_status_codes.internal_status_code', '=', 'orders.internal_status_code')
                            ->join('users', 'users.users_id', '=', 'orders.seller_user_id')
                            ->join('order_items', 'order_items.orders_id', '=', 'orders.orders_id')
                            ->join('order_shipping_details', 'order_shipping_details.orders_id', '=', 'orders.orders_id')
                            ->join('products', 'products.products_id', '=', 'order_items.products_id')
                            ->join('product_shipping_details', 'product_shipping_details.products_id', '=', 'products.products_id')
                            ->join('skus', 'skus.products_id', '=', 'products.products_id')
                            ->join('payment_details', 'payment_details.payment_ref_id', '=', 'orders.payment_ref_id')
                            ->leftJoin('pg_status_codes', 'pg_status_codes.pg_status_code', '=', 'payment_details.pg_status_code')
                            ->join('providers', 'providers.providers_id', '=', 'payment_details.shares_providers_id')
                            ->where('orders.orders_id', $orders_id)
                            ->get([
                                'products.products_id',
                                'products.product_name',
                                'products.product_description',
                                'products.currency',
                                'skus.selling_price',
                                'product_shipping_details.shipping_charge',
                                'products.created_at as products_created_at',
                                'users.first_name',
                                'users.username',
                                'internal_status_codes.status_description',
                                'orders.created_at as orders_created_at',
                                'payment_details.customers_name',
                                'payment_details.customers_contact_number',
                                'order_shipping_details.shipping_address',
                                'order_shipping_details.shipping_city',
                                'order_shipping_details.shipping_state',
                                'order_shipping_details.shipping_country',
                                'order_shipping_details.shipping_pincode',
                                'order_shipping_details.shipping_landmark',
                                'order_shipping_details.shipping_service',
                                'order_shipping_details.tracking_number',
                                'order_shipping_details.comments',
                                'order_items.order_items_price',
                                'providers.providers_name',
                                'payment_details.payment_ref_id',
                                'payment_details.payment_mode',
                                'payment_details.total_amount as total_amount_paid',
                                'payment_details.customers_email_address',
                                'pg_status_codes.pg_status_description'
                            ]);
        
        $data               = json_decode(json_encode($order_data[0]), true);
        $data['product_url']        = config("base_urls.product_share_url").$data['products_id'];
        $data['products_created_at']= Carbon::createFromFormat('Y-m-d H:i:s', $data['products_created_at'])->toDayDateTimeString();
        $data['orders_created_at']  = Carbon::createFromFormat('Y-m-d H:i:s', $data['orders_created_at'])->toDayDateTimeString();
        $order_history_data     = DB::table('orders')
                                    ->join('order_history', 'order_history.orders_id', '=', 'orders.orders_id')
                                    ->join('internal_status_codes', 'internal_status_codes.internal_status_code', '=', 'order_history.internal_status_code')
                                    ->where('orders.orders_id', $orders_id)
                                    ->get([
                                        'order_history.internal_status_code',
                                        'internal_status_codes.status_description',
                                        'order_history.created_at'
                                    ]);
        $data['order_history']  = json_decode(json_encode($order_history_data), true);
        for($i=0; $i<count($data['order_history']); $i++)
        {
            $data['order_history'][$i]['created_at']    = Carbon::createFromFormat('Y-m-d H:i:s', $data['order_history'][$i]['created_at'])->toDayDateTimeString();
        }
        
        return view('v1.orders-show', $data);
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
