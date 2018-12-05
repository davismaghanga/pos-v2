<?php

namespace App\Jobs;

use App\Facades\SMS;
use App\Jobs\Job;
use App\Sale;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class DailyReminder extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        //get all completed jobs and foward the owners to collect them
        $sales=Sale::where([['job_status',2]])->orderBy('id','desc')->get();
        foreach ($sales as $sale) {
            //Determine if the sale is still in sms domain
            if(Sale::find($sale->id)->job_status==2){
                //sms the sale customer their job is done
                $customer=$sale->Customer;
                if($customer!=null){
                    $sms=" " . $customer->name . ". Your order no: " . $sale->id . " is ready for collection.";
//                    if($sale->balance!=0){
//                        $sms.="Remaining balance is Ksh. " . $sale->balance;
//                    }
                    SMS::send($sms, $customer->phone,$customer->business);
                    Log::warning("Sms collection reminder " . $customer->phone);

                }
            }else{
                Log::warning("Repeated sms reminder skipped");
            }

        }
    }
}
