<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Datatables;

use App\User;

use DB;

use App\DataTables\UsersDataTable;



class DatatablesController extends Controller
{
    public function getIndex(UsersDataTable $dataTable)
    {
//        return view('datatables.eloquent.advance-filter');
        return $dataTable->render('datatables.eloquent.advance-filter');
    }

    public function anyData()
    {
//        $users = DB::table('users')->select(['users_id','username', 'created_at']);
//       
//          return Datatables::of($users)->make(true);
        
        
    }
}


