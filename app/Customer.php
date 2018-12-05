<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    public function scopeLike($query, $field, $value) {
        return $query->where($field, 'LIKE', "%$value%")->where('business', session('business_id'));
    }


    protected $guarded = [];

    public function Business()
    {
        return $this->hasOne(Business::class,'id','business');
    }
}
