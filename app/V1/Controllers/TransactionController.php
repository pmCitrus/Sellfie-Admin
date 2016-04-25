<?php

namespace App\V1\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use DB;

use App\DataTables\PaymentDetailsDataTable;

class TransactionController extends Controller
{
    public function index(PaymentDetailsDataTable $datatable)
    {
        return $datatable->render('v1.transactions');
    }
    
    public function show($id)
    {
        echo $id;
    }
}
