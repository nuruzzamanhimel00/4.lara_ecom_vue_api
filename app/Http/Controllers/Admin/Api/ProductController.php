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
        // return response()->json([
        //    $request->all()
        // ]);
        $id = $request->id;
        $prodcut_img_toArray_db = ProductImage::where('product_id',$request->id)
        ->pluck('image')->toArray();
        $form_product_imgArray_file = $request->input('files');

        $diff_array = array_diff($prodcut_img_toArray_db,$form_product_imgArray_file);

        // $array_diff_to_find_oldInmg_forRemove = array_diff($prodcut_img_toArray_db,$form_product_imgArray_file);
        // $array_intersect_to_find_newImg_forStore = array_intersect($form_product_imgArray_file,$prodcut_img_toArray_db);


        // return response()->json([
        //      $diff_array
        // ]);

        if(empty($diff_array)){
            $product_create_get_id = DB::table('products')->where('id',$id)->update([
                "userid" => $request->userid,
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

            return response()->json([
                'status' => 'success'
            ]);
        }else{
            $emptyArray = [];
            foreach($diff_array as $da){
                if(File::exists(public_path('image/admin/products/product_items/'.$da))) {
                    File::delete(public_path('image/admin/products/product_items/'.$da));
                }
                $productImageDelete = ProductImage::where('product_id',$request->id)
                ->where('image',$da)->delete();
                $emptyArray[] = $da;

            }

            foreach($form_product_imgArray_file as $file){
                if (strpos($file, 'data') !== false) {
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
                            "product_id" => $id,
                            "image" => $imagename
                        ]);
                    }


                }
            }
            return response()->json([
                'status' => 'success'
            ]);
        }
    //     return response()->json([
    //         $emptyArray
    //    ]);


        // dd();
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
