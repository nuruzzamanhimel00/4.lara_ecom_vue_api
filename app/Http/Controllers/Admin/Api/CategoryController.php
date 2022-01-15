<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductCategory;
use Illuminate\Support\Facades\File;
use Image;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $category = ProductCategory::orderBy('id','desc')->get();
        return response()->json([
            'status' => "success",
            'data' => $category
        ]);
    }

    public function catWithProductCount()
    {

        $category = ProductCategory::withCount(['products'])->orderBy('id','desc')->get();
        return response()->json([
            'status' => "success",
            'data' => $category
        ]);
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
        $category = ProductCategory::where('id',$id)->first();
        return response()->json([
            'status' => "success",
            'data' => $category
        ]);
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
        $this->validate($request, [
            'name' => 'required|unique:product_categories',
            // 'image' => 'required',
        ]);

        // return response()->json([
        //     $request->all(), $id
        // ]);

        $image = $request->image;
        $category = ProductCategory::where('id',$id)->first();

        if($category->image != $image ){


            if(File::exists(public_path('image/admin/products/'.$category->image))) {
                File::delete(public_path('image/admin/products/'.$category->image));
            }
               // data:image/png;
            $strpos = strpos($image,';');
            $substr = substr($image,0,$strpos);
            $image_ext = substr(strrchr($substr,'/'),1);


            $imagename = $request->name."_".rand().".".$image_ext;
            $image_save = public_path('image/admin/products/'.$imagename);

            // open an image file
            $img = Image::make($request->image);

            // save image in desired format
            if($img->save($image_save)){

                $udtProductCat = ProductCategory::where('id',$id)->update([
                    'name' => $request->name,
                    'image' => $imagename,
                ]);
                if(isset($udtProductCat)){
                    return response()->json([
                        'status' => 'success'
                    ]);
                }
            }
        }else{
               // data:image/png;
               $udtProductCat = ProductCategory::where('id',$id)->update([
                'name' => $request->name,

                ]);
                if(isset($udtProductCat)){
                    return response()->json([
                        'status' => 'success'
                    ]);
                }
        }




    }


    public function destroy($id)
    {
        $category = ProductCategory::where('id',$id)->first();
        if(File::exists(public_path('image/admin/products/'.$category->image))) {
            File::delete(public_path('image/admin/products/'.$category->image));
        }
        if($category->delete()){
            return response()->json([
                'status' => 'success'
            ]);
        }
    }
}
