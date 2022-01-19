<?php

namespace App\Http\Controllers\Fontend\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductCategory;
use App\Products;

class CategoryController extends Controller
{
    public function getCetegoryByCatid($catid){
        $getCategory = ProductCategory::where('id',$catid)->first();
        return response()->json([
            'status' => "success",
            'data' => $getCategory
        ]);
    }

    public function getCategoryWiseProducts($catid){
        $products = Products::with(['images'])->where('categoryId',$catid)->get();
        return response()->json([
            'status' => "success",
            'data' => $products
        ]);
    }
}
