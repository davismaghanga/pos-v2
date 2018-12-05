<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\View;

class ReceiptController extends Controller
{
    //

    public function getIndex(){
        return View::make('receipt');
    }

    public function getPrintOut(){
        return View::make('receipt')->with('print',true);
    }

    public function getCloseOut(){
        session(['current_receipt'=>""]);

        return redirect('sales');
    }
}
