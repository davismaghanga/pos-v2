<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleEntry extends Model
{
    //

    public function Sale() {
        return $this->hasOne('App\Sale', 'id', 'sale');
    }

    public function Product() {
        if ($this->is_service){
            return $this->hasOne('App\Service', 'id', 'product');
        }
        return $this->hasOne('App\Product', 'id', 'product');
    }
}
