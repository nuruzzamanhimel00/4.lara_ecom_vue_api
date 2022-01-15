<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductBrand;
use Illuminate\Support\Facades\File;
use Image;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = ProductBrand::orderBy('id','desc')->get();
        return response()->json([
            'status' => "success",
            'data' => $brands
        ]);
    }
    public function barndWithProductCount()
    {
        $brands = ProductBrand::withCount(['brands'])->orderBy('id','desc')->get();
        return response()->json([
            'status' => "success",
            'data' => $brands
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
        // return response()->json([
        //     $request->all()
        // ]);
        $this->validate($request, [
            'name' => 'required|unique:product_brands',
            'image' => 'required',
        ]);
        $image = $request->image;
        // data:image/png;
        $strpos = strpos($image,';');
        $substr = substr($image,0,$strpos);
        $image_ext = substr(strrchr($substr,'/'),1);


        $imagename = $request->name."_".rand().".".$image_ext;
        $image_save = public_path('image/admin/products/brands/'.$imagename);

        // open an image file
        $img = Image::make($request->image);

        // save image in desired format
        if($img->save($image_save)){

            $addBrand = ProductBrand::create([
                'name' => $request->name,
                'image' => $imagename,
            ]);
            if(isset($addBrand)){
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
        $brnad = ProductBrand::where('id',$id)->first();
        return response()->json([
            'status' => "success",
            'data' => $brnad
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
        $brand = ProductBrand::where('id',$id)->first();

        if($brand->image != $image ){


            if(File::exists(public_path('image/admin/products/brands/'.$brand->image))) {
                File::delete(public_path('image/admin/products/brands/'.$brand->image));
            }
               // data:image/png;
            $strpos = strpos($image,';');
            $substr = substr($image,0,$strpos);
            $image_ext = substr(strrchr($substr,'/'),1);


            $imagename = $request->name."_".rand().".".$image_ext;
            $image_save = public_path('image/admin/products/brands/'.$imagename);

            // open an image file
            $img = Image::make($request->image);

            // save image in desired format
            if($img->save($image_save)){

                $udtBrand = ProductBrand::where('id',$id)->update([
                    'name' => $request->name,
                    'image' => $imagename,
                ]);
                if(isset($udtBrand)){
                    return response()->json([
                        'status' => 'success'
                    ]);
                }
            }
        }else{
               // data:image/png;
               $udtBrand = ProductBrand::where('id',$id)->update([
                'name' => $request->name,

                ]);
                if(isset($udtBrand)){
                    return response()->json([
                        'status' => 'success'
                    ]);
                }
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brnad = ProductBrand::where('id',$id)->first();
        if(File::exists(public_path('image/admin/products/brands/'.$brnad->image))) {
            File::delete(public_path('image/admin/products/brands/'.$brnad->image));
        }
        if($brnad->delete()){
            return response()->json([
                'status' => 'success'
            ]);
        }
    }
}
