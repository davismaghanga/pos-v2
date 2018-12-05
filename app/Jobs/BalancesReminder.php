<?php

namespace App\Jobs;

use App\Sale;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class BalancesReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        //

        //fetch sale with balances with customer greater than 48 hrs and must be collected
        $two_days_ago = Carbon::now()->subDay(2);
        $sales=Sale::where('balance','<',0)
            ->where('created_at','<',$two_days_ago)
            ->where('job_status',3)
            ->has('Customer')
            ->with('Customer')
            ->get();


        //foreach of them
        foreach ($sales as $sale){
            //if customer has phone number send reminder
            $customer=$sale->Customer;
            if($customer->phone !=null ){
                $sms=" " . $customer->name . ". Your order ID: " . $sale->id . " requires your attention.";
                $sms .= " Remaining balance is Ksh. " . $sale->balance;
                SMS::send($sms, $customer->phone, $customer->business);
                Log::warning("Sms completion reminder " . $customer->phone . $sms);
            }

        }
    }
}
