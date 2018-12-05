<?php

namespace App\Http\Controllers;

use App\Jobs\DailyReminder;
use App\Jobs\FowardDueJobs;
use App\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class SheduledController extends Controller
{
    //

    public function index()
    {
        
    }

    public function getProcessing()
    {
        $sales=Sale::where([['business', session('business_id')],['job_status',1]])->orderBy('id','desc')->limit(500)->get();
        return View::make("orders.processing")->with('sales',$sales)->with('page','Completed orders');
    }

    public function getCompleted()
    {
        $sales=Sale::where([['business', session('business_id')],['job_status',2]])->orderBy('id','desc')->limit(500)->get();
        return View::make("orders.complete")->with('sales',$sales)->with('page','Completed orders');
    }

    public function getCollected()
    {
        $sales=Sale::where([['business', session('business_id')],['job_status',3]])->orderBy('id','desc')->limit(500)->get();
        return View::make("orders.collected")->with('sales',$sales)->with('page','Collected orders');
    }

    public function postComplete(Request $request)
    {
        $sale=Sale::find($request->input('sale_id'));
        $sale->job_status=2;
        $sale->Save();
        return back();
    }

    public function postCollect(Request $request)
    {
        $sale=Sale::find($request->input('sale_id'));
        $sale->job_status=3;
        $sale->Save();
        return back();
    }

    public function getJob()
    {
        //dispatch the processed jobs checker
        dispatch(new FowardDueJobs());

        //dispatch the daily checker
        $current_hour=Carbon::now()->format('H');
        Log::warning($current_hour);
        if(in_array($current_hour,[21])){
            dispatch(new DailyReminder());
        }
        return "Job dispatched";
    }
}
