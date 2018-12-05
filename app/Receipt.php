<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    //
    public function Customer() {
        return $this->hasOne('App\Customer', 'id', 'customer');
    }

    public function Cashier()
    {
        return $this->hasOne(User::class,'id','cashier');
    }

    public function Sale()
    {
        return $this->hasOne(Sale::class,'id','sale');
    }
}
