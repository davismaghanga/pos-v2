<?php

namespace App\Console\Commands;

use App\Jobs\DailyReminder;
use App\Jobs\FowardDueJobs;
use App\DailyReminder as ReminderRecord;
use App\Sale;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SheduleCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    private $designated_hour=[8];


    protected $signature = 'sales:collect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collects sales to their respective sales';

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
        //
        $processing=Sale::where([['job_status',1],['expected_completion','<',Carbon::now()->toDateTimeString()]])->count();
        if($processing>0){
            dispatch(new FowardDueJobs());
        }

        Log::info("Invoked order processor, active items -> " . $processing);

        $done_checker=ReminderRecord::where([['date',Carbon::now()->format('Y-m-d')],['hour',Carbon::now()->format('H')],['type',0]])->count();
        if(in_array(Carbon::now()->format('H'),$this->designated_hour)) {

            if ($done_checker <= 0){
                dispatch(new DailyReminder());
                $r=new ReminderRecord();
                $r->hour=Carbon::now()->format('H');
                $r->date=Carbon::now()->format('Y-m-d');
                $r->Save();
            }
        }
    }
}
