<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    public function Category() {
        return $this->hasOne('App\Category', 'id', 'category');
    }

    public function scopeLike($query, $field, $value) {
        return $query->where($field, 'LIKE', "%$value%")->where('business', session('business_id'));
    }
}
