<?php

namespace App\V1\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use DB;
use Sentinel;

use App\DataTables\ProductsDataTable;
use App\DataTables\Scopes\ProductActiveScope;
use App\DataTables\Scopes\ProductDisabledScope;
use App\DataTables\Scopes\ProductReportedScope;

class ProductController extends Controller
{
    public function index(ProductsDatatable $datatable, Request $request)
    {
        switch(Request::segment(2))
        {
            case 'active':
                $scope          = new ProductActiveScope();
                $view           = 'v1.products-active';
                $query_columns  = [
                                    'products.products_id',
                                    'product_name',
                                    'selling_price',
                                    'quantity_total',
                                    'products.is_admin_approved',
                                    'first_name',
                                    'profile_name',
                                    'profile_user_name',
                                    'username',
                                    'products.created_at'
                                    ];
                $export_columns = [
                                    'products_id',
                                    'product_name',
                                    'selling_price',
                                    'quantity_total',
                                    'first_name',
                                    'profile_name',
                                    'profile_user_name',
                                    'username',
                                    'created_at'
                                    ];
                break;
            
            case 'disabled':
                $scope          = new ProductDisabledScope();
                $view           = 'v1.products-disabled';
                $query_columns  = [
                                    'products.products_id',
                                    'product_name',
                                    'selling_price',
                                    'quantity_total',
                                    'products.is_admin_approved',
                                    'first_name',
                                    'profile_name',
                                    'profile_user_name',
                                    'username',
                                    'products.created_at'
                                    ];
                $export_columns = [
                                    'products_id',
                                    'product_name',
                                    'selling_price',
                                    'quantity_total',
                                    'first_name',
                                    'profile_name',
                                    'profile_user_name',
                                    'username',
                                    'created_at'
                                    ];
                break;
            
            case 'reported':
                $scope          = new ProductReportedScope();
                $view           = 'v1.products-reported';
                $query_columns  = [
                                    'products.products_id',
                                    'product_name',
                                    'selling_price',
                                    'reported.reported_customers_mobile',
                                    'reported.reported_customers_name',
                                    'reported.user_comments',
                                    'reported_counter.report_count',
                                    'first_name',
                                    'profile_name',
                                    'profile_user_name',
                                    'username',
                                    'products.created_at'
                                    ];
                $export_columns = [
                                    'products_id',
                                    'product_name',
                                    'selling_price',
                                    'reported_customers_mobile',
                                    'reported_customers_name',
                                    'user_comments',
                                    'report_count',
                                    'first_name',
                                    'profile_name',
                                    'profile_user_name',
                                    'username',
                                    'created_at'
                                    ];
                break;
            
            default:
                $scope          = new ProductActiveScope();
                $view           = 'v1.products-active';
                $query_columns  = [
                                    'products.products_id',
                                    'product_name',
                                    'selling_price',
                                    'quantity_total',
                                    'products.is_admin_approved',
                                    'first_name',
                                    'profile_name',
                                    'profile_user_name',
                                    'username',
                                    'products.created_at'
                                    ];
                $export_columns = [
                                    'products_id',
                                    'product_name',
                                    'selling_price',
                                    'quantity_total',
                                    'first_name',
                                    'profile_name',
                                    'profile_user_name',
                                    'username',
                                    'created_at'
                                    ];
        }
        $datatable->setQueryColumns($query_columns);
        $datatable->setExportColumns($export_columns);
        $datatable->addScope($scope);
        
        if($datatable->request()->get('start_date'))
        {
            $column_name    = 'products.created_at';
            $start_date     = $datatable->request()->get('start_date').' 00:00:00';
            $end_date       = $datatable->request()->get('end_date').' 23:59:59';
            $scope_search   = new CommonDateSearchScope($column_name, $start_date, $end_date);
            $datatable->addScope($scope_search);
        }
        
        return $datatable->render($view);
    }
    
    public function approve()
    {
        $products_id                        = Request::segment(3);
        $update_array['updated_at']         = date('Y-m-d H:i:s');
        $update_array['is_admin_approved']  = 'y';
        $update_array['approved_by' ]       = Sentinel::getUser()->users_id;
        
        DB::table('products')
            ->where('products_id', $products_id)
            ->update($update_array);
        
        return \Redirect::route('products', [Request::segment(2)]);
    }
    
    public function edit()
    {
        $products_id                = Request::segment(3);
        $action_type                = Request::segment(4);
        $update_array['updated_at'] = date('Y-m-d H:i:s');
        
        switch($action_type)
        {
            case 'enable':
                $update_array['is_active']  = 'y';
                break;
            
            case 'disable':
                $update_array['is_active']  = 'n';
                break;
            
            default:
                $update_array['is_active']  = 'y';
                break;
        }
        
        DB::table('products')
            ->where('products_id', $products_id)
            ->update($update_array);
        
        return \Redirect::route('products', [Request::segment(2)]);
    }
    
    public function destroy()
    {
        $products_id                = Request::segment(3);
        $update_array['deleted_at'] = date('Y-m-d H:i:s');
        $update_array['updated_at'] = date('Y-m-d H:i:s');
        $update_array['is_active']  = 'n';
        $update_array['deleted_by'] = Sentinel::getUser()->users_id;
        
        DB::table('products')
            ->where('products_id', $products_id)
            ->update($update_array);
        
        return \Redirect::route('products', [Request::segment(2)]);
    }
    
    public function ignore()
    {
        $products_id                        = Request::segment(3);
        $update_array['updated_at']         = date('Y-m-d H:i:s');
        $update_array['is_admin_ignored']   = 'y';
        $update_array['ignored_by' ]        = Sentinel::getUser()->users_id;
        
        DB::table('reported')
                ->where('parameter_source', 'product')
                ->where('parameter_source_id', $products_id)
                ->update($update_array);
        
        return \Redirect::route('products', [Request::segment(2)]);
    }
}
