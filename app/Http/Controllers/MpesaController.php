<?php

namespace App\Http\Controllers;

use App\PaymentConfirmation;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Response;

class MpesaController extends Controller
{
    //

    public function __construct(){
        $this->middleware(['auth', 'userAccess']);
    }

    public function postFetchLastTransaction(Request $request)
    {
        $transaction=PaymentConfirmation::where([['msisdn',$request->input('phone_number')],['state',0]])->orderBy('id','desc')->first();
        if($transaction==null){
            return Response::json([
                'success'=>false
            ]);
        }

        return Response::json([
            'success'=>true,
            'transaction'=>$transaction
        ]);
    }
}
