<?php

namespace App\Http\Controllers;

use App\Business;
use App\Facades\SMS;
use App\PaymentConfirmation;
use App\Receipt;
use App\Sale;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BalancesController extends Controller
{
    //
    public function __construct(){
        $this->middleware(['auth', 'userAccess']);
    }

    public function getIndex(){
        return view('balances');
    }


    public function postPayBalance(Request $request)
    {
        $data = array(  'channel' => $request->input('channel'),
            'amount' => $request->input('amount'),
            'code' => $request->input('mpesa_code'),
            'visa' => $request->input('visa_code'),
            'sale' => $request->input('sale_id'));

        $sale = Sale::find($data['sale']);
        $customer = $sale->Customer;
        $oldBalance = $sale->balance;
        $bal = 0;
        $points = 0;

        switch ($data['channel']) {
            case 0:
                //visa

                //TODO: check visa entry

                //add a new receipt
                $receipt = new Receipt();
                $receipt->business = session('business_id');
                $receipt->channel = 3;
                $receipt->code = $data['visa'];
                $receipt->customer = $sale->customer;
                $receipt->sale = $data['sale'];
                $receipt->cashier=Auth::id();
                $receipt->amount = $data['amount'];
                $receipt->status = 1;
                $receipt->save();

                //reduce customer balance
                $customer->balance += $data['amount'];
                $customer->save();

                //reduce sale balance
                $sale->balance += $data['amount'];
                $sale->save();

                break;
            case  1:
                //mpesa

                //add a new receipt
                $receipt = new Receipt();
                $receipt->business = session('business_id');
                $receipt->channel = 2;
                $receipt->code = $data['code'];
                $receipt->customer = $sale->customer;
                $receipt->sale = $data['sale'];
                $receipt->amount = $data['amount'];
                $receipt->status = 1;
                $receipt->cashier=Auth::id();
                $receipt->save();

                if($request->has('t_id') && $request->input('t_id')!=""){
                    $payconf=PaymentConfirmation::find($request->input('t_id'));
                    $payconf->state=1;
                    $payconf->Save();
                }

                //reduce customer balance
                $customer->balance += $data['amount'];
                $customer->save();

                //reduce sale balance
                $sale->balance += $data['amount'];
                $sale->save();

                break;
            case 2:
                //cash

                //validate
                $validator = Validator::make($request->all(), [
                    'amount' => 'numeric|max:' . -$sale->balance,
                ], [
                    'amount.max' => 'Amount paid is more than needed.',
                ]);

                if ($validator->fails()) {
                    return redirect('balances')
                        ->withErrors($validator)
                        ->withInput()
                        ->with('payBalance', true);
                }

                $receipt = new Receipt();
                $receipt->business = session('business_id');
                $receipt->sale = $data['sale'];
                $receipt->channel = 1;
                $receipt->code = 0;
                $receipt->amount = $data['amount'];
                $receipt->customer = $customer->id;
                $receipt->status = 1;
                $receipt->cashier=Auth::id();
                $receipt->save();


                //reduce customer balance
                $customer->balance += $data['amount'];
                $customer->save();

                //update receipt balance
                $b=$sale->balance+$data['amount'];
                $receipt->balance=($b>=0)?0:$b;
                $receipt->Save();

                //reduce sale balance
                $sale->balance += $data['amount'];
                $sale->status = 1;
                $sale->save();

                break;

        }



        //add customer points
        $business = Business::find(session('business_id'));
        if ($sale->amount >= $business->loyalty_min_earn && $business->loyalty_earn_rate > 0){
            $points = floor($data['amount'] / $business->loyalty_earn_rate);
            $customer->loyalty_points += $points;
            $customer->save();
        }

        //send sms
        $msg = " " . $customer->name . " \n";
        $msg .= "Your invoice " . $sale->id . " settled as:\n";
        $msg .= "Previous Bal: " . abs($oldBalance) . "\nPaid: " . $data['amount'] . "\nNew Bal: " . abs($sale->balance) . "\nPoints earned: " . $points . "\n";

        SMS::send($msg, $customer->phone);

        session(['current_sale_id' => 0]);

        return redirect('balances');
    }

    public function postSearch(Request $request) {
        $value = $request->input('search');
        $result = Sale::like('name', $value)->where('balance', "<", '0')->get();

        return redirect('balances')->withInput()->with('sales', $result);
    }
}
