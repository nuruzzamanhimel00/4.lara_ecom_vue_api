<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [''];

    public function product()
    {
        return $this->belongsTo('App\Products','productid',"id");
    }
    public function productImages()
    {
        return $this->belongsTo('App\ProductImage','productid',"product_id");
    }

}
