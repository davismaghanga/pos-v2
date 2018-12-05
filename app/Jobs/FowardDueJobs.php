<?php

namespace App\Jobs;

use App\Facades\SMS;
use App\Jobs\Job;
use App\Sale;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class FowardDueJobs extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //get all processing sales whose time is due
        Log::error('wrong invoke');

        $sales=Sale::where([['job_status',1],['expected_completion','<',Carbon::now()->toDateTimeString()]])->orderBy('id','desc')->limit(500)->get();

        foreach ($sales as $sale) {
            //check if sale is still in sms domain
            if(Sale::find($sale->id)->job_status==1) {

                //sms the sale customer their job is done
                $customer = $sale->Customer;
                if ($customer != null) {
                    $sms = " " . $customer->name . ". Your order no " . $sale->id . " is ready for collection.";

                    // $sms .= ". Contact us 0721879663.";
                    SMS::send($sms, $customer->phone, $customer->business);
                    Log::warning("Sms completion reminder " . $customer->phone);
                    $sale->job_status = 2;
                    $sale->Save();

                }
            }else{
                Log::warning("Repeated sms completion skipped");
            }
        }

    }
}
