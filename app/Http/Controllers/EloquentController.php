<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class EloquentController extends Controller
{
    public function getMultiFilterSelect()
    {
        return view('datatables.eloquent.advance-filter');
    }

    public function getMultiFilterSelectData()
    {
        $users = DB::table('users')->select(['username', 'created_at']);

        return Datatables::of($users)->make(true);
    }
}
