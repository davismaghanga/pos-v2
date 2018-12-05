<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Sale extends Model
{
    //

    protected  $dates=['expected_completion'];


    public function User() {
        return $this->hasOne('App\User', 'id', 'by_user');
    }

    public function Customer() {
        return $this->hasOne('App\Customer', 'id', 'customer');
    }



    public function Receipts() {
        return $this->hasMany('App\Receipt', 'sale', 'id');
    }

    public function scopeLike($query, $field, $value) {
        return $query->where($field, 'LIKE', "%$value%")->where('business', session('business_id'));
    }

    public function myCustomer(){
        return $this->belongsTo('App\Customer', 'customer', 'id');
    }
}
