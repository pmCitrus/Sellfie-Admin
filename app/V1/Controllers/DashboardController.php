<?php

namespace App\V1\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
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
        
        return $this->loadView($start_date, $end_date, 'n');
    }
    
    public function show()
    {
        $request    = Request::all();
        $start_date = $request['start_date']." 00:00:00";
        $end_date   = $request['end_date']." 23:59:59";
        return $this->loadView($start_date, $end_date, 'y');
    }
    
    public function loadView($start_date, $end_date, $is_custom)
    {
        $data['total_active_sellers']           = DB::table('user_profiles')
                                                    ->join('users', 'users.users_id', '=', 'user_profiles.users_id')
                                                    ->whereBetween('user_profiles.created_at', [$start_date, $end_date])
                                                    ->whereNotNull('profile_name')
                                                    ->whereNotNull('profile_description')
                                                    ->whereNotNull('profile_user_name')
                                                    ->where('users.block_status', 'n')
                                                    ->whereNull('users.deleted_at')
                                                    ->count();
        
        $data['total_sellers']                  = DB::table('role_users')
                                                    ->join('roles', 'roles.id', '=', 'role_users.role_id')
                                                    ->join('users', 'users.users_id', '=', 'role_users.user_id')
                                                    ->whereBetween('role_users.created_at', [$start_date, $end_date])
                                                    ->where('roles.slug', '=', 'sellfie-users')
                                                    ->where('users.block_status', 'n')
                                                    ->whereNull('deleted_at')
                                                    ->count();
        
        $data['total_products_created']         = DB::table('products')
                                                    ->whereBetween('created_at', [$start_date, $end_date])
                                                    ->whereIn('is_admin_approved', ['y', 'na'])
                                                    ->whereNull('deleted_at')
                                                    ->count();
        
        $data['total_collect_links_created']    = DB::table('payment_links')
                                                    ->whereBetween('created_at', [$start_date, $end_date])
                                                    ->whereIn('is_admin_approved', ['y', 'na'])
                                                    ->whereNull('deleted_at')
                                                    ->count();
        
        $data['total_orders_placed']            = DB::table('order_history')
                                                    ->whereBetween('created_at', [$start_date, $end_date])
                                                    ->where('internal_status_code', '=', config('status_codes.order_status_codes.PaymentCompleted'))
                                                    ->count();
        
        $data['total_orders_completed']         = DB::table('order_history')
                                                    ->whereBetween('created_at', [$start_date, $end_date])
                                                    ->where('internal_status_code', '=', config('status_codes.order_status_codes.OrderCompleted'))
                                                    ->count();
        
        $data['total_collect_payments']         = DB::table('payment_details')
                                                    ->whereBetween('created_at', [$start_date, $end_date])
                                                    ->where('payment_source', '=', 'payment_link')
                                                    ->where('pg_status_code', '=', '0')
                                                    ->sum('total_amount');
        
        $data['total_sellfie_commission']       = DB::table('settlements')
                                                    ->whereBetween('created_at', [$start_date, $end_date])
                                                    ->where('settlement_status', '=', 'SETTLED')
                                                    ->sum('sellfie_commission');
        
        if($is_custom == 'y')
        {
            $data['is_custom']  = 'y';
        }
        $data['start_date']     = Carbon::createFromFormat('Y-m-d H:i:s', $start_date)->toDateString();
        $data['end_date']       = Carbon::createFromFormat('Y-m-d H:i:s', $end_date)->toDateString();
        
        return view('v1.dashboard', $data);
    }
}
