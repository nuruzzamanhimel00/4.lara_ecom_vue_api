<?php

namespace App\Http\Controllers\Fontend\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductBrand;
use App\Products;

class BrandController extends Controller
{
    public function brandByBrandid($brandid){
        $brand = ProductBrand::where('id',$brandid)->first();
        return response()->json([
            'status' => "success",
            'data' => $brand
        ]);
    }
    public function getBrandsProducts($brandid){

        $products = Products::with(['images'])->where('brandId',$brandid)->get();
        return response()->json([
            'status' => "success",
            'data' => $products
        ]);
    }
}
