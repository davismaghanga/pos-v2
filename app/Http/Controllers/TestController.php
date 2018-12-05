<?php

namespace App\Http\Controllers;

use App\Jobs\BalancesReminder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Facades\SMS;

use App\Http\Requests;

class TestController extends Controller
{
    //
    public function getRun(){
        $allResults = [
            [ 'id' => 1 , 'rank' => 100 ],
            [ 'id' => 2 , 'rank' => 99 ],
            [ 'id' => 3 , 'rank' => 102 ],
            [ 'id' => 80000 , 'rank' => 3 ],
        ];
        $rankings = [];
        foreach ($allResults as $result) {
            $rankings[] = implode( ', ' , [ '"' . $result[ 'id' ] . '"' , $result[ 'rank' ]]);
        }

        $rankings = Collection ::make($rankings);
        $rankings->chunk( 2 )->each
        (function ($ch) {
            $rankingString = '';
            foreach ($ch as $ranking) {
                $rankingString .= '(' . $ranking .
                    '), ';
                echo $ranking, "<br>";
            }
            $rankingString = rtrim($rankingString, ", " );
            echo $rankingString, "<br>";
        });
    }

    public function getIndex(){
    
    	

    }
    
    public function sms(){
//            $fields=array("message" => "hello", "recipients"=>"0718942538");
//    		echo SMS::send("hello","0700034834",7);

        $this->dispatch(new BalancesReminder());


    }
}
