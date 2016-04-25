<?php

namespace App\V1\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

use App\DataTables\UserKycDataTable;
use App\DataTables\Scopes\KycNewScope;
use App\DataTables\Scopes\KycApprovedScope;
use App\DataTables\Scopes\KycRejectedScope;

use App\UserKyc;
use Sentinel;

class KycController extends Controller
{
    public function index(UserKycDataTable $datatable)
    {
        switch(Request::segment(2))
        {
            case 'new':
                $scope          = new KycNewScope();
                $view           = 'v1.kyc-new';
                $query_columns  = [
                                    'first_name',
                                    'profile_name',
                                    'user_kycs.kyc_ref_id',
                                    'user_kycs.submitted_at',
                                    'users.block_status',
                                    'deleted_at'//,
//                                    'settlement_status'
                                    ];
                $export_columns = [
                                    'first_name',
                                    'profile_name',
                                    'kyc_ref_id',
                                    'submitted_at',
                                    'block_status',
                                    'deleted_at' //,
//                                    'settlement_status'
                                    ];
                break;
            
            case 'approved':
                $scope          = new KycApprovedScope();
                $view           = 'v1.kyc-approved';
                $query_columns  = [
                                    'users.first_name',
                                    'profile_name',
                                    'user_kycs.kyc_ref_id',
                                    'user_kycs.approved_at',
                                    'admin_users.email as admins_email',
                                    'users.block_status',
                                    'users.deleted_at'
                                    ];
                $export_columns = [
                                    'first_name',
                                    'profile_name',
                                    'kyc_ref_id',
                                    'approved_at',
                                    'admins_email',
                                    'block_status',
                                    'deleted_at'
                                    ];
                break;
            
            case 'rejected':
                $scope          = new KycRejectedScope();
                $view           = 'v1.kyc-rejected';
                $query_columns  = [
                                    'users.first_name',
                                    'profile_name',
                                    'user_kycs.kyc_ref_id',
                                    'user_kycs.rejected_at',
                                    'admin_users.email as admins_email',
                                    'users.block_status',
                                    'users.deleted_at'
                                    ];
                $export_columns = [
                                    'first_name',
                                    'profile_name',
                                    'kyc_ref_id',
                                    'rejected_at',
                                    'admins_email',
                                    'block_status',
                                    'deleted_at'
                                    ];
                break;
            
            default:
                $query_columns  = [
                                    'first_name',
                                    'profile_name',
                                    'user_kycs.kyc_ref_id',
                                    'user_kycs.submitted_at',
                                    'users.block_status',
                                    'deleted_at'//,
//                                    'settlement_status'
                                    ];
                $export_columns = [
                                    'first_name',
                                    'profile_name',
                                    'kyc_ref_id',
                                    'submitted_at',
                                    'block_status',
                                    'deleted_at' //,
//                                    'settlement_status'
                                    ];
                break;
        }
        
        $datatable->setQueryColumns($query_columns);
        $datatable->setExportColumns($export_columns);
        $datatable->addScope($scope);
        
        if($datatable->request()->get('start_date'))
        {
            $column_name    = 'user_kycs.submitted_at';
            $start_date     = $datatable->request()->get('start_date').' 00:00:00';
            $end_date       = $datatable->request()->get('end_date').' 23:59:59';
            $scope_search   = new CommonDateSearchScope($column_name, $start_date, $end_date);
            $datatable->addScope($scope_search);
        }
        
        return $datatable->render($view);
    }
    
    public function show()
    {
        $kyc_ref_id = Request::segment(3);
        
        echo $kyc_ref_id;
    }
    
    public function update()
    {
        $kyc_ref_id                             = Request::segment(3);
        $action_type                            = Request::segment(4);
        $update_data['action_done_by']          = Sentinel::getUser()->users_id;
        $update_data['updated_at']              = date('Y-m-d H:i:s');
        $update_history_data['action_done_by']  = $update_data['action_done_by'];
        
        if($action_type == 'approve')
        {
            $update_data['is_admin_approved']       = 'y';
            $update_data['approved_at']             = date('Y-m-d H:i:s');
            $update_history_data['approval_status'] = 'approved';
            $update_history_data['updated_at']      = date('Y-m-d H:i:s');
        }
        else if($action_type == 'reject')
        {
            $update_data['is_admin_approved']       = 'n';
            $update_data['rejected_at']             = date('Y-m-d H:i:s');
            $update_history_data['approval_status'] = 'rejected';
            $update_history_data['updated_at']      = date('Y-m-d H:i:s');
        }
        
        $user_kycs_history_id    = DB::table('user_kycs_history')
                                        ->join('user_kycs', 'user_kycs.user_kycs_id', '=', 'user_kycs_history.user_kycs_id')
                                        ->where('user_kycs.kyc_ref_id', $kyc_ref_id)
                                        ->max('user_kycs_history.user_kycs_history_id');
        
        $updated    = DB::table('user_kycs')
                    ->where('kyc_ref_id', $kyc_ref_id)
                    ->update($update_data);
        if($updated)
        {
            DB::table('user_kycs_history')
                    ->where('user_kycs_history_id', $user_kycs_history_id)
                    ->update($update_history_data);
        }
        
        //settlement status update to remove any on hold
        
        return \Redirect::route('kyc', [Request::segment(2)]);
    }
}
