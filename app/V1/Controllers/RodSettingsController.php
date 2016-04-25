<?php

namespace App\V1\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use DB;

class RodSettingsController extends Controller
{
    public function index()
    {
        $rod_data   = DB::table('default_rods')
                        ->select('product_rod_days', 'collect_link_rod_days')
                        ->whereNull('deleted_at')
                        ->get();
        
        $data['product_rod_days']       = $rod_data[0]->product_rod_days;
        $data['collect_link_rod_days']  = $rod_data[0]->collect_link_rod_days;
        
        return view('v1.rod-settings', $data);
    }
    
    public function store()
    {
        $request    = Request::all();
        
        switch($request['rod_type'])
        {
            case 'product':
                $column_name    = 'product_rod_days';
                break;
            case 'collect_link':
                $column_name    = 'collect_link_rod_days';
                break;
            default:
                $column_name    = 'product_rod_days';
                break;
        }
        
        DB::table('default_rods')
            ->where('default_rods_id', 1)
            ->update([$column_name => $request['rod_days']]);
        
        return redirect('rod_settings');
    }
}
