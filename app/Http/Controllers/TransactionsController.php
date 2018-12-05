<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Sale;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

use App\Http\Requests;

class TransactionsController extends Controller
{
    //


    public function __construct(){
        $this->middleware(['auth']);
    }

    public function getIndex(){
        return redirect('transactions/transactionsreport');
    }
    public function getTransactionsreport(){
            return view('transactions.transactions');
    }
    public function getFiltershit(Request $request)
    {
        return redirect('transactions/transactionsreport');
    }

    public function postFiltershit(Request $request){

        $customer=Customer::find($request->input('search_id'));
        $sales=Sale::where('customer','=',$request->input('search_id'))->get();


        return View::make('transactions.transactions')->with('results',array('customer'=>$customer,'sales'=>$sales));

    }

    public function getSuggestions(){
        $Customers=Customer::where('business', session('business_id'))->select(['name','id','phone'])->get();
        $data=array();
        foreach ($Customers as $customer){
            $data[]=array('name'=>$customer->name . "(" . $customer->phone . ")",'id'=>$customer->id);
        }
        return response()->json($data);
    }
}
