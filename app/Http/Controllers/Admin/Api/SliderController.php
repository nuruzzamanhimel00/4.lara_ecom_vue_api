<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Slider;
use DB;
use Image;
use File;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::orderBy('id','desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $sliders
        ]);
    }

    public function lastprority(){
        $lastPrority = Slider::orderBy('id','desc')->first();
        if(!is_null($lastPrority)){
            return response()->json([
                'status' => 'success',
                'data' => $lastPrority->prority+1
            ]);
        }else{
            return response()->json([
                'status' => 'success',
                'data' => 1
            ]);
        }
    }

    public function sliderStatus(){
        $status = DB::table('status')->get();
        return response()->json([
            'status' => 'success',
            'data' => $status
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
            'title' => 'required',
            'image' => 'required',
            'prority' => 'required',
            'status' => 'required',
        ]);

        $image = $request->input('image');
        // data:image/png;


        $strpos = strpos($image,';');
        $substr = substr($image,0,$strpos);
        $image_ext = substr(strrchr($substr,'/'),1);

        $imagename = substr(uniqid(rand()),0,8).".".$image_ext;
        $image_save = public_path('image/admin/Sliders/'.$imagename);

        // open an image file
        $img = Image::make($image);
        if($img->save($image_save)){

            $addProductCat = Slider::create([
                'title' => $request->title,
                'image' => $imagename,
                'prority' => $request->prority,
                'status' => $request->status,
            ]);
            if(isset($addProductCat)){
                return response()->json([
                    'status' => 'success'
                ]);
            }
        }


        return response()->json([
            $request->all() , $image
        ]);


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
        $this->validate($request, [
            'title' => 'required',
            'image' => 'required',
            'prority' => 'required',
            'status' => 'required',
        ]);

        $slider = Slider::where('id',$id)->first();
        if($slider->image != $request->input('image')){

            if(File::exists(public_path('image/admin/Sliders/'.$slider->image ))) {
                File::delete(public_path('image/admin/Sliders/'.$slider->image ));
            }

            $image = $request->input('image');
            // data:image/png;

            $strpos = strpos($image,';');
            $substr = substr($image,0,$strpos);
            $image_ext = substr(strrchr($substr,'/'),1);

            $imagename = substr(uniqid(rand()),0,8).".".$image_ext;
            $image_save = public_path('image/admin/Sliders/'.$imagename);

            // open an image file
            $img = Image::make($image);
            if($img->save($image_save)){

                $addProductCat = Slider::where('id',$id)->update([
                    'title' => $request->title,
                    'image' => $imagename,
                    'prority' => $request->prority,
                    'status' => $request->status,
                ]);
                if(isset($addProductCat)){
                    return response()->json([
                        'status' => 'success'
                    ]);
                }
            }
        }else{
            $addProductCat = Slider::where('id',$id)->update([
                'title' => $request->title,

                'prority' => $request->prority,
                'status' => $request->status,
            ]);
            if(isset($addProductCat)){
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
        $slider = Slider::where('id',$id)->first();
        if(File::exists(public_path('image/admin/Sliders/'.$slider->image ))) {
            File::delete(public_path('image/admin/Sliders/'.$slider->image ));
        }
        if($slider->delete()){
            return response()->json([
                'status' => 'success'
            ]);
        }
    }
}
