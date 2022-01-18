<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    protected $guarded = [""];

    public function brnadproducts()
    {
        return $this->hasMany('App\Products','brandId','id');
    }

}
