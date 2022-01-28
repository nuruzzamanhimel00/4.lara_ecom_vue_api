<?php

namespace App\Http\Controllers\Fontend\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductCategory;
use App\ProductImage;
use App\Products;
use App\ProductStatus;
use DB;
use Image;
use App\Library\AcccessToken;
use App\ProductBrand;
use Hamcrest\Arrays\IsArray;
use File;

class ProductController extends Controller
{

    public function index()
    {
        $products = Products::with(['category','brand','user','productStatus','images'])->orderBy("id",'desc')->get();
        return response()->json([
            'status' => "success",
            'data' => $products
        ]);
    }
    public function getAllProductsPaginate(Request $request)
    {
        $products = Products::with(['category','brand','user','productStatus','images'])
        ->orderBy("id",'desc')
        ->paginate(6);
        return response()->json([
            'status' => "success",
            'data' => $products
        ]);
    }
    public function productShow( $slug)
    {
        $product = Products::with(['category','brand','images'])
                    ->where('slug',$slug)->first();

        return response()->json([
            'status' => "success",
            'data' => $product
        ]);
    }
    public function productSearch(Request $request){
        // return response()->json([
        //     // 'status' => "success",
        //     'data' => $request->all()
        // ]);
        $search = $request->search;
        $products = Products::with(['category','brand','images'])
                    ->where(function($query) use($search){
                        $query->where('title','like','%'.$search.'%')
                       ->orWhere('description','like','%'.$search.'%')
                       ->orWhere('slug','like','%'.$search.'%');
                    })->get();
        // if(count($products) <= 0){
        //     $products = Products::with(['category','brand','images'])
        //     ->get();
        // }

        return response()->json([
            'status' => "success",
            'data' => $products
        ]);
    }

    public function getProductStatus(){
        $getProductStatus = ProductStatus::get();
        return response()->json([
            'status' => 'success',
            'data' => $getProductStatus
        ]);
    }

    public function getBrandWiseProdcutByBrandid($brandid){

        $brand = ProductBrand::with(['brnadproducts'])->where('id',$brandid)->first();
        return response()->json([
            'status' => "success",
            'data' => $brand
        ]);
    }



    public function create()
    {
        //
    }


    public function store(Request $request)
    {

    }


    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
