<?php

namespace App\V1\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use DB;


class CommissionSettingsController extends Controller
{
    public function index()
    {
        $commission_data    = DB::table('default_commissions')
                                ->select('commission_type', 'commission_value')
                                ->whereNull('deleted_at')
                                ->get();
        
        foreach($commission_data as $d)
        {
            if($d->commission_type == 'percentage')
            {
                $data['percentage_commission']  = $d->commission_value;
            }
            else if($d->commission_type == 'fixed')
            {
                $data['fixed_commission']       = $d->commission_value;
            }
        }
        
        return view('v1.commission-settings', $data);
    }
    
    public function store()
    {
        $request    = Request::all();
        
        DB::table('default_commissions')
            ->where('commission_type', $request['commission_type'])
            ->update(['commission_value' => $request['commission_value']]);
        
        return redirect('commission_settings');
    }
}
