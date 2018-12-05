<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsUnitsUpdate extends Model
{
    //

    public function Changer()
    {
        return $this->hasOne(User::class,'id','changer_id');
    }
}
