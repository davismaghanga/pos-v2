<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class CanSendSmsController extends Controller
{
    //
    public function getDisable(){

        $user=Auth::User();
        $user->can_send_sms=0;
        $user->Save();
        return redirect()->back();

    }

    public function getEnable(){

        $user=Auth::User();
        $user->can_send_sms=1;
        $user->Save();
        return redirect()->back();

    }
}
