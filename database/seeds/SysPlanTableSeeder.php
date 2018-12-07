<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SysPlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sys_plans')->insert([
            'name'=>'Basic',
            'description'=>'Basic Plan',
            'price'=>1500,


        ]);
    }
}
