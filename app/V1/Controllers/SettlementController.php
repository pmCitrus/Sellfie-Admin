<?php

namespace App\V1\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
//use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\URL;

use App\Repositories\SettlementRepository;

class SettlementController extends Controller
{
    public function index()
    {
//        $settlement_repository  = new SettlementRepository();
        return view('v1.settlements-upload');
    }
    
    public function store()
    {
        echo "<pre>";
        
//        $request    = Request::all();
        $destinationPath    = storage_path('uploads/settlements_pg_file_upload');
        $file_name          = date('d_M_Y_His');
        if (Request::hasFile('pg_file')) {
            Request::file('pg_file')->move($destinationPath, $file_name);
            Excel::load($destinationPath."/".$file_name, function ($reader) {

                foreach ($reader->toArray() as $row) {
                    print_r($row);
                }
            });
        }
        else
        {
            echo "none!";
        }
    }
    
    public function downloadView()
    {
//        $settlement_repository  = new SettlementRepository();
        return view('v1.settlements-download');
    }
}
