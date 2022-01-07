<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $guarded=[""];

    public function category()
    {
        return $this->belongsTo('App\ProductCategory','categoryId',"id");
    }

    public function brand()
    {
        return $this->belongsTo('App\ProductBrand','brandId',"id");
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','userid',"id");
    }
    public function productStatus()
    {
        return $this->belongsTo('App\ProductStatus','productstatus_id',"id");
    }
    public function images()
    {
        return $this->hasMany('App\ProductImage','product_id','id');
    }

}
