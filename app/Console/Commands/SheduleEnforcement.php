<?php

namespace App\Console\Commands;

use App\Jobs\BalancesReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\DailyReminder as ReminderRecord;


class SheduleEnforcement extends Command
{
    private $designated_hour=[20];


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales:enforce';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send out balances reminders for all old balances(dispatch Balances Reminder)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $done_checker=ReminderRecord::where([['date',Carbon::now()->format('Y-m-d')],['hour',Carbon::now()->format('H')],['type',1]])->count();
        if(in_array(Carbon::now()->format('H'),$this->designated_hour) && $done_checker <=0) {

            dispatch(new BalancesReminder());
            $r=new ReminderRecord();
            $r->hour=Carbon::now()->format('H');
            $r->date=Carbon::now()->format('Y-m-d');
            $r->type=1;
            $r->Save();
            Log::info("Dispatched a balances reminder task, executing...");

        }
    }
}
