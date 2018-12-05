<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Facades\Sms;

class SmsController extends Controller
{
    //
    public function Test(){
        echo Sms::invoke('0712888795');
    }
}
