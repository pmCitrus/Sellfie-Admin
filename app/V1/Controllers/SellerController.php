<?php

namespace App\V1\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;

use App\DataTables\SellersDataTable;
use App\DataTables\Scopes\SellerSignedUpDataTableScope;
use App\DataTables\Scopes\SellerActiveDataTableScope;
use App\DataTables\Scopes\SellerDeletedDataTableScope;
use App\DataTables\Scopes\SellerBannedDataTableScope;
use App\DataTables\Scopes\SellerCommonScope;
use App\DataTables\Scopes\CommonDateSearchScope;

use DB;
use Sentinel;
use Log;

class SellerController extends Controller
{
    public function index(SellersDataTable $datatable, Request $request)
    {
        $common_scope   = new SellerCommonScope();
        switch(Request::segment(2))
        {
            case 'sign_up':
                $scope          = new SellerSignedUpDataTableScope();
                $view           = 'v1.sellers-signup';
                $query_columns  = [
                                    'users.users_id',
                                    'first_name',
                                    'username',
                                    'users.email',
                                    'device_imei',
                                    'area',
                                    'last_login_ip',
                                    'products_bought',
                                    'users.created_at'
                                    ];
                $export_columns = [
                                    'users_id',
                                    'first_name',
                                    'username',
                                    'email',
                                    'device_imei',
                                    'area',
                                    'last_login_ip',
                                    'products_bought',
                                    'created_at'
                                    ];
                break;
            
            case 'active':
                $scope          = new SellerActiveDataTableScope();
                $view           = 'v1.sellers-active';
                $query_columns  = [
                                    'users.users_id',
                                    'first_name',
                                    'username',
                                    'users.email',
                                    'users.created_at',
                                    'profile_name',
                                    'products_created',
                                    'collect_links_created',
                                    'user_kycs.is_admin_approved',
                                    'profile_user_name'
                                    ];
                $export_columns = [
                                    'users_id',
                                    'first_name',
                                    'username',
                                    'email',
                                    'created_at',
                                    'profile_name',
                                    'products_created',
                                    'collect_links_created',
                                    'is_admin_approved',
                                    'profile_user_name'
                                    ];
                $datatable->addScope($common_scope);
                break;
            
            case 'deleted':
                $scope          = new SellerDeletedDataTableScope();
                $view           = 'v1.sellers-deleted';
                $query_columns  = [
                                    'users.users_id',
                                    'first_name',
                                    'username',
                                    'users.email',
                                    'users.created_at',
                                    'profile_name',
                                    'products_created',
                                    'collect_links_created',
                                    'user_kycs.is_admin_approved',
                                    'profile_user_name'
                                    ];
                $export_columns = [
                                    'users_id',
                                    'first_name',
                                    'username',
                                    'email',
                                    'created_at',
                                    'profile_name',
                                    'products_created',
                                    'collect_links_created',
                                    'is_admin_approved',
                                    'profile_user_name'
                                    ];
                $datatable->addScope($common_scope);
                break;
            
            case 'banned':
                $scope          = new SellerBannedDataTableScope();
                $view           = 'v1.sellers-banned';
                $query_columns  = [
                                    'users.users_id',
                                    'users.first_name',
                                    'users.username',
                                    'users.created_at',
                                    'users.deleted_at',
                                    'profile_name',
                                    'block_reason',
                                    'admin_users.email as admins_email',
                                    'products_created',
                                    'collect_links_created',
                                    'orders_completed'
                                    ];
                $export_columns = [
                                    'users_id',
                                    'first_name',
                                    'username',
                                    'created_at',
                                    'deleted_at',
                                    'profile_name',
                                    'block_reason',
                                    'admins_email',
                                    'products_created',
                                    'collect_links_created',
                                    'orders_completed'
                                    ];
                $datatable->addScope($common_scope);
                break;
            
            default:
                $scope          = new SellerSignedUpDataTableScope();
                $view           = 'v1.sellers-signup';
                $query_columns  = [
                                    'users.users_id',
                                    'first_name',
                                    'username',
                                    'users.email',
                                    'device_imei',
                                    'area',
                                    'last_login_ip',
                                    'users.created_at'
                                    ];
                $export_columns = [
                                    'users_id',
                                    'first_name',
                                    'username',
                                    'email',
                                    'device_imei',
                                    'area',
                                    'last_login_ip',
                                    'created_at'
                                    ];
        }
        
        $datatable->setQueryColumns($query_columns);
        $datatable->setExportColumns($export_columns);
        $datatable->addScope($scope);
        
        if($datatable->request()->get('start_date'))
        {
            $column_name    = 'users.created_at';
            $start_date     = $datatable->request()->get('start_date').' 00:00:00';
            $end_date       = $datatable->request()->get('end_date').' 23:59:59';
            $scope_search   = new CommonDateSearchScope($column_name, $start_date, $end_date);
            $datatable->addScope($scope_search);
        }
        
        return $datatable->render($view);
    }
    
    public function updateView()
    {
        $users_id                   = Request::segment(3);
        echo $users_id;
        
        return \Redirect::route('sellers', [Request::segment(2)]);
    }
    
    public function update()
    {
        $users_id                   = Request::segment(3);
        echo $users_id;
        
        return \Redirect::route('sellers', [Request::segment(2)]);
    }
    
    public function enable()
    {
        $users_id                   = Request::segment(3);
        $update_array['deleted_at'] = NULL;
        $update_array['updated_at'] = date('Y-m-d H:i:s');
        
        $updated    = DB::table('users')
                    ->where('users_id', $users_id)
                    ->update($update_array);
        
        if($updated)
        {
            DB::table('products')
                    ->where('users_id', $users_id)
                    ->update([
                        'is_active' => 'y',
                        'updated_at'=> date('Y-m-d H:i:s')
                        ]);
            
           DB::table('payment_links')
                   ->where('users_id', $users_id)
                    ->update([
                            'is_active' => 'y',
                            'updated_at'=> date('Y-m-d H:i:s')
                            ]);
            
            //put settlements back for process
        }
        
        return \Redirect::route('sellers', [Request::segment(2)]);
    }
    
    public function delete()
    {
        $users_id                   = Request::segment(3);
        $update_array['deleted_at'] = date('Y-m-d H:i:s');
        $update_array['updated_at'] = date('Y-m-d H:i:s');
        
        $updated    = DB::table('users')
                        ->where('users_id', $users_id)
                        ->update($update_array);
        if($updated)
        {
            DB::table('products')
                    ->where('users_id', $users_id)
                    ->update([
                        'is_active' => 'n',
                        'updated_at'=> date('Y-m-d H:i:s')
                        ]);
            
            DB::table('payment_links')
                    ->where('users_id', $users_id)
                    ->update([
                            'is_active' => 'n',
                            'updated_at'=> date('Y-m-d H:i:s')
                            ]);
            
//            DB::table('settlements')
//                    ->where('users_id', $users_id)
//                    ->update([
//                            'settlement_status' => config('settlement_status_code'),
//                            'updated_at'=> date('Y-m-d H:i:s')
//                            ]);
        }
        
        return \Redirect::route('sellers', [Request::segment(2)]);
    }
    
    public function ban()
    {
        $request    = Request::all();
        $update_array['block_status']   = 'y';
        $update_array['deleted_at']     = date('Y-m-d H:i:s');
        $update_array['updated_at']     = date('Y-m-d H:i:s');
        
        $updated    = DB::table('users')
                        ->where('users_id', $request['users_id'])
                        ->update($update_array);
        
        if($updated)
        {
            $insert_data['users_id']    = $request['users_id'];
            $insert_data['block_reason']= $request['block_reason'];
            $insert_data['blocked_by']  = Sentinel::getUser()->users_id;
            $insert_data['created_at']  = date('Y-m-d H:i:s');
            $insert_data['updated_at']  = date('Y-m-d H:i:s');
            DB::table('user_block_details')->insert($insert_data);
            
            DB::table('products')
                    ->where('users_id', $request['users_id'])
                    ->update([
                        'is_active' => 'n',
                        'updated_at'=> date('Y-m-d H:i:s')
                        ]);
            
            DB::table('payment_links')
                    ->where('users_id', $request['users_id'])
                    ->update([
                            'is_active' => 'n',
                            'updated_at'=> date('Y-m-d H:i:s')
                            ]);
            
//            DB::table('settlements')
//                    ->where('users_id', $request['users_id'])
//                    ->update([
//                            'settlement_status' => config('settlement_status_code'),
//                            'updated_at'=> date('Y-m-d H:i:s')
//                            ]);
        }
        
        return \Redirect::route('sellers', [Request::segment(2)]);
    }
}
