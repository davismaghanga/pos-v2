<?php

namespace App\Http\Controllers;

use App\Receipt;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;

class ReceiptTransactionsController extends Controller
{
    //
    public function getIndex()
    {

        if(session('_old_input')==null){
            Session::flash('_old_input',['t_type'=>'']);
        }
        return View::make('transactions.index')->with('page','transactions');
    }

    public function postFilter(Request $request)
    {

        //get the date range
        $startDate=$request->input('start_date');
        $endDate=$request->input('end_date');

        $user=$request->input('user');

        if ($user == 0) {
            $receipts = Receipt::where('business', session('business_id'))
                ->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($user != 0) {
            $receipts = Receipt::where('business', session('business_id'))
                ->where('cashier', $user)
                ->whereBetween('created_at', [$startDate, $endDate]);
        }


        //build the type filter
        if($request->has('t_type') && $request->input('t_type')==null){

        }
        else if($request->has('t_type') && $request->input('t_type')==0 ){
            //visa
            $receipts->where('channel',3);
        }
        else if($request->has('t_type') && $request->input('t_type')==1){
            //mpesa
            $receipts->where('channel',2);
        }
        else if($request->has('t_type') && $request->input('t_type')==2){
          //cash
            $receipts->where('channel',1);
        }else if ($request->has('t_type') && $request->input('t_type')==3){
          //loyalty
            $receipts->where('channel',4);
        }

        //return json_encode($receipts->get());


        $receipts = $receipts->get();

        
        return redirect('rTransactions')->withInput()->with('receipts', $receipts);

    }

    public function getExport(Request $request)
    {

        //get the date range
        $startDate=$request->input('start_date');
        $endDate=$request->input('end_date');

        $user=$request->input('user');

        if ($user == 0) {
            $receipts = Receipt::where('business', session('business_id'))
                ->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($user != 0) {
            $receipts = Receipt::where('business', session('business_id'))
                ->where('cashier', $user)
                ->whereBetween('created_at', [$startDate, $endDate]);
        }

        //build the type filter
        if($request->has('t_type') && $request->input('t_type')==null){

        }
        else if($request->has('t_type') && $request->input('t_type')==0 ){
            //visa
            $receipts->where('channel',3);
        }
        else if($request->has('t_type') && $request->input('t_type')==1){
            //visa
            $receipts->where('channel',2);
        }
        else if($request->has('t_type') && $request->input('t_type')==2){
            $receipts->where('channel',1);
        }


        $receipts = $receipts->get();

        Session::flash('data',$receipts);

        if($request->has('pdf')){

        }else{
            //export excel
            Excel::create('New file', function($excel) {

                $excel->sheet('New sheet', function($sheet) {

                    $sheet->loadView('transactions.table',array('receipts'=>session('data')));

                });

            })->download('xlsx');
        }
    }
}
