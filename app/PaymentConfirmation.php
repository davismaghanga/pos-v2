<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PaymentConfirmation extends Model
{
    //
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
