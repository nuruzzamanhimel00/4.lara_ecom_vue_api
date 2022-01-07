<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductCategory;
use App\ProductImage;
use App\Products;
use App\ProductStatus;
use DB;
use Image;
use App\Library\AcccessToken;

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

    public function getProductStatus(){
        $getProductStatus = ProductStatus::get();
        return response()->json([
            'status' => 'success',
            'data' => $getProductStatus
        ]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {

        // return response()->json([
        //     $request->all()
        // ]);
        $this->validate($request, [
            'brandId' => 'required|integer',
            'categoryId' => 'required|integer',
            'quantity' => 'required|integer',
            'price' => 'required|integer',
            'productstatus_id' => 'required|integer',
            // 'offer_price' => 'integer',
            'title' => 'required',
            'description' => 'required',
            'files' => 'required',
            // 'files.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);


        $product_create_get_id = DB::table('products')->insertGetId([
            "userid" => $request->adminAuthId,
            "categoryId" => $request->categoryId,
            "brandId" => $request->brandId,
            "title" => $request->title,
            "description" => $request->description,
            "slug" => str_slug($request->title) ,
            "quantity" => $request->quantity,
            "price" => $request->price,
            "offer_price" => isset($request->offer_price)? $request->offer_price : NULL,
            "productstatus_id" => $request->productstatus_id,
            'ip_address' => \Request::ip()
        ]);

        foreach($request->input('files') as $file){
            $image = $file;
            // data:image/png;
            $strpos = strpos($image,';');
            $substr = substr($image,0,$strpos);
            $image_ext = substr(strrchr($substr,'/'),1);

            $imagename = str_slug($request->title)."_".rand().".".$image_ext;
            $image_save = public_path('image/admin/products/product_items/'.$imagename);

             // open an image file
            $img = Image::make($image);

            // save image in desired format
            if($img->save($image_save)){
                $productImage = ProductImage::create([
                    "product_id" => $product_create_get_id,
                    "image" => $imagename
                ]);
            }

        }


        return response()->json([
           'status' => 'success'
        ]);

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
