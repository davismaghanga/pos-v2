<?php

namespace App\Plugins\SMS;

use App\Business;
use App\User;
use Illuminate\Support\Facades\Log;

class SMS {

    private $systemExtension = "";
    private $business;

    public function _send($fields){
        $user=User::where([['business',$this->business->id],['level',1]])->first();
        if($user!=null&&$user->can_send_sms){
           echo $this->_from_parent($fields);
        }
    }

    public function _from_parent($fields)
    {

        $to_send=json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://api.sematime.com/v1/1506529101270/messages");
        if ($this->business->sms_has_custom){
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                'ApiKey: ' . $this->business->sms_api_key));
        }else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                'ApiKey: b3f8367087654ee286c87304eddff6da'));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $to_send);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        Log::warning($response);

        //parse response for the consumed units
            if($response==null || $response=="" || $response==false){}
            else{
                $decoded = json_decode($response,true);
                $consumed_units=0;
                if ($decoded['statusCode'] == 200 || $decoded['statusCode'] == "200") {

                    $consumed_units = ($decoded['consumedUnits']);
                }else {
                    $consumed_units = 0;
                }

                //update users consumed units increase
                if($this->business!=null){
                    $this->business->units_consumed+=$consumed_units;
                    $this->business->Save();
                }
            }


            return $response;
    }

    public function send_user_intro($message, $number){
        $this->business = Business::find(session('business_id'));
        $fields=array("message" => $this->business->sms_greeting . $message . "\n" . $this->business->sms_extension . "\n" . $this->systemExtension, "recipients"=>$number);
        return $this->_send($fields);
    }

    public function send($message, $number,$business_id=""){

        if ($business_id == ""){
            $this->business = Business::find(session('business_id'));
        }else{
            $this->business = Business::find($business_id);
        }

//        $businessContact = "Contact Us: " . $this->business->phone . " \n";
//        the above line was concatenaterd with every sms with bus phne number
        $fields=array("message" => $this->business->sms_greeting . $message . "\n" . $this->business->sms_extension . "\n" . "" . $this->systemExtension, "recipients"=>$number);

//       Log::warning(json_encode($this->business));
         return $this->_send($fields);

    }
}