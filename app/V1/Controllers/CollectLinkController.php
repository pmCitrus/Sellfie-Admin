<?php

namespace App\V1\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use DB;
use Sentinel;

use App\DataTables\CollectLinksDataTable;
use App\DataTables\Scopes\CollectLinkActiveScope;
use App\DataTables\Scopes\CollectLinkDisabledScope;
use App\DataTables\Scopes\CollectLinkReportedScope;

class CollectLinkController extends Controller
{
    public function index(CollectLinksDataTable $datatable, Request $request)
    {
        switch(Request::segment(2))
        {
            case 'active':
                $scope          = new CollectLinkActiveScope();
                $view           = 'v1.collect_links-active';
                $query_columns  = [
                                    'payment_links.payment_links_id',
                                    'payment_link_title',
                                    'payment_link_amount',
                                    'payment_links.is_admin_approved',
                                    'first_name',
                                    'profile_name',
                                    'profile_user_name',
                                    'username',
                                    'payment_links.created_at'
                                    ];
                $export_columns = [
                                    'payment_links_id',
                                    'payment_link_title',
                                    'payment_link_amount',
                                    'first_name',
                                    'profile_name',
                                    'profile_user_name',
                                    'username',
                                    'created_at'
                                    ];
                break;
            
            case 'disabled':
                $scope          = new CollectLinkDisabledScope();
                $view           = 'v1.collect_links-disabled';
                $query_columns  = [
                                    'payment_links.payment_links_id',
                                    'payment_link_title',
                                    'payment_link_amount',
                                    'first_name',
                                    'profile_name',
                                    'profile_user_name',
                                    'username',
                                    'payment_links.created_at'
                                    ];
                $export_columns = [
                                    'payment_links_id',
                                    'payment_link_title',
                                    'payment_link_amount',
                                    'first_name',
                                    'profile_name',
                                    'profile_user_name',
                                    'username',
                                    'created_at'
                                    ];
                break;
            
            case 'reported':
                $scope          = new CollectLinkReportedScope();
                $view           = 'v1.collect_links-reported';
                $query_columns  = [
                                    'payment_links.payment_links_id',
                                    'payment_link_title',
                                    'payment_link_amount',
                                    'reported.reported_customers_mobile',
                                    'reported.reported_customers_name',
                                    'reported.user_comments',
                                    'reported_counter.report_count',
                                    'first_name',
                                    'profile_name',
                                    'profile_user_name',
                                    'username',
                                    'payment_links.created_at'
                                    ];
                $export_columns = [
                                    'payment_links_id',
                                    'payment_link_title',
                                    'payment_link_amount',
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
                $scope          = new CollectLinkActiveScope();
                $view           = 'v1.collect_links-active';
                $query_columns  = [
                                    'payment_links.payment_links_id',
                                    'payment_link_title',
                                    'payment_link_amount',
                                    'payment_links.is_admin_approved',
                                    'first_name',
                                    'profile_name',
                                    'profile_user_name',
                                    'username',
                                    'payment_links.created_at'
                                    ];
                $export_columns = [
                                    'payment_links_id',
                                    'payment_link_title',
                                    'payment_link_amount',
                                    'first_name',
                                    'profile_name',
                                    'profile_user_name',
                                    'username',
                                    'created_at'
                                    ];
                break;
        }
        $datatable->setQueryColumns($query_columns);
        $datatable->setExportColumns($export_columns);
        $datatable->addScope($scope);
        
        if($datatable->request()->get('start_date'))
        {
            $column_name    = 'payment_links.created_at';
            $start_date     = $datatable->request()->get('start_date').' 00:00:00';
            $end_date       = $datatable->request()->get('end_date').' 23:59:59';
            $scope_search   = new CommonDateSearchScope($column_name, $start_date, $end_date);
            $datatable->addScope($scope_search);
        }
        
        return $datatable->render($view);
    }
    
    public function approve()
    {
        $payment_links_id                   = Request::segment(3);
        $update_array['updated_at']         = date('Y-m-d H:i:s');
        $update_array['is_admin_approved']  = 'y';
        $update_array['approved_by' ]       = Sentinel::getUser()->users_id;
        
        DB::table('payment_links')
            ->where('payment_links_id', $payment_links_id)
            ->update($update_array);
        
        return \Redirect::route('collect_links', [Request::segment(2)]);
    }
    
    public function edit()
    {
        $payment_links_id           = Request::segment(3);
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
        
        DB::table('payment_links')
            ->where('payment_links_id', $payment_links_id)
            ->update($update_array);
        
        return \Redirect::route('collect_links', [Request::segment(2)]);
    }
    
    public function destroy()
    {
        $payment_links_id           = Request::segment(3);
        $update_array['updated_at'] = date('Y-m-d H:i:s');
        $update_array['is_active']  = 'n';
        $update_array['is_expired'] = 'y';
        $update_array['deleted_at'] = date('Y-m-d H:i:s');
        $update_array['deleted_by'] = Sentinel::getUser()->users_id;
        
        DB::table('payment_links')
            ->where('payment_links_id', $payment_links_id)
            ->update($update_array);
        
        return \Redirect::route('collect_links', [Request::segment(2)]);
    }
    
    public function ignore()
    {
        $payment_links_id                   = Request::segment(3);
        $update_array['updated_at']         = date('Y-m-d H:i:s');
        $update_array['is_admin_ignored']   = 'y';
        $update_array['ignored_by' ]        = Sentinel::getUser()->users_id;
        
        DB::table('reported')
                ->where('parameter_source', 'payment_link')
                ->where('parameter_source_id', $payment_links_id)
                ->update($update_array);
        
        return \Redirect::route('collect_links', [Request::segment(2)]);
    }
}
