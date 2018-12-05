<?php

namespace App\Http\Controllers;

use App\Facades\SMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotifyController extends Controller
{
    //
    public function notify(Request $request)
    {
        $sms=" " . request('kyc_name') . " we have received your payment of Ksh. " . request('trans_amount') . " via Mpesa. Ref no " . request('trans_id') . " in favor offered service";
        echo SMS::send($sms,"+" .request('msisdn') ,7);

    }
}
