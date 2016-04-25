<?php

namespace App\V1\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
{
    public function index()
    {
        $end_date   = Carbon::now();
        
        switch(Request::segment(2))
        {
            case 'weekly':
                $start_date = Carbon::today()->modify('-1 week')->toDateTimeString();
                break;
            case 'monthly':
                $start_date = Carbon::today()->modify('-1 month')->toDateTimeString();
                break;
            case 'quarterly':
                $start_date = Carbon::today()->modify('-3 months')->toDateTimeString();
                break;
            case 'half_yearly':
                $start_date = Carbon::today()->modify('-6 months')->toDateTimeString();
                break;
            default:
                $start_date = Carbon::today()->toDateTimeString();
                break;
        }
        
        $data['total_active_sellers']           = DB::table('user_profiles')
                                                    ->whereBetween('created_at', [$start_date, $end_date])
                                                    ->whereNotNull('profile_name')
                                                    ->whereNotNull('profile_description')
                                                    ->whereNotNull('profile_user_name')
                                                    ->count();
        
        $data['total_sellers']                  = DB::table('role_users')
                                                    ->whereBetween('created_at', [$start_date, $end_date])
                                                    ->where('role_id', '=', '4')
                                                    ->count();
        
        $data['total_products_created']         = DB::table('products')
                                                    ->whereBetween('created_at', [$start_date, $end_date])
                                                    ->where('is_admin_approved', '=', 'y')
                                                    ->whereNotNull('deleted_at')
                                                    ->count();
        
        $data['total_collect_links_created']    = DB::table('payment_links')
                                                    ->whereBetween('created_at', [$start_date, $end_date])
                                                    ->where('is_admin_approved', '=', 'y')
                                                    ->count();
        
        $data['total_orders_placed']            = DB::table('order_history')
                                                    ->whereBetween('created_at', [$start_date, $end_date])
                                                    ->where('orders_status_code', '=', config('status_codes.order_status_codes.PaymentCompleted'))
                                                    ->count();
        
        $data['total_orders_completed']         = DB::table('order_history')
                                                    ->whereBetween('created_at', [$start_date, $end_date])
                                                    ->where('orders_status_code', '=', config('status_codes.order_status_codes.OrderCompleted'))
                                                    ->count();
        
        $data['total_collect_payments']         = DB::table('payment_details')
                                                    ->whereBetween('created_at', [$start_date, $end_date])
                                                    ->where('payment_source', '=', 'payment_link')
                                                    ->where('pg_status_code', '=', '0')
                                                    ->sum('total_amount');
        
        $data['total_sellfie_commission']       = '0'; //DB::table('settlements')
//                                                    ->whereBetween('created_at', [$start_date, $end_date])
//                                                    ->where('is_admin_approved', '=', 'y')
//                                                    ->count();
        
        return view('v1.dashboard', $data);
    }
}
