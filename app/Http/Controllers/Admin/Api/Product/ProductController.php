<?php

namespace App\Http\Controllers\Admin\Api\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductCategory;
use Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:product_categories',
            'categoryImage' => 'required',
        ]);
        $image = $request->categoryImage;
        // data:image/png;
        $strpos = strpos($image,';');
        $substr = substr($image,0,$strpos);
        $image_ext = substr(strrchr($substr,'/'),1);


        $imagename = $request->name."_".rand().".".$image_ext;
        $image_save = public_path('image/admin/products/'.$imagename);

        // open an image file
        $img = Image::make($request->categoryImage);

        // save image in desired format
        if($img->save($image_save)){

            // return response()->json([
            //     $imageurl
            // ]);

            $addProductCat = ProductCategory::create([
                'name' => $request->name,
                'image' => $imagename,
            ]);
            if(isset($addProductCat)){
                return response()->json([
                    'status' => 'success'
                ]);
            }
        }





    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
