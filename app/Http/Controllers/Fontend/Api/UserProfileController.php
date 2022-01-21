<?php

namespace App\Http\Controllers\Fontend\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use File;
use Image;

class UserProfileController extends Controller
{
    public function userProfileUpdate(Request $request){
        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->id,
            'image' => 'required',
            'phone_no' => 'required',
            'present_address' => 'required',
            'shipping_address' => 'required',
        ]);
        // 'email' => 'required|email|unique:users,email,'.$this->user->id

        $user = User::find($request->id);

        if($user->image != $request->image){
            if(File::exists(public_path('image/fontend/user/'.$request->image))) {
                File::delete(public_path('image/fontend/user/'.$request->image));
            }

            $image = $request->input('image');
            // data:image/png;
            $strpos = strpos($image,';');
            $substr = substr($image,0,$strpos);
            $image_ext = substr(strrchr($substr,'/'),1);


            $imagename = $request->firstname."_".rand().".".$image_ext;
            $image_save = public_path('image/fontend/user/'.$imagename);

            // open an image file
            $img = Image::make($request->image);

            // save image in desired format
            if($img->save($image_save)){

                    $updateUser = User::where('id',$request->id)->update([
                        'firstname' => $request->firstname,
                        'lastname' => $request->lastname,
                        'email' => $request->email,
                        'image' => $imagename,
                        'phone_no' => $request->phone_no,
                        'ip_address'=>$request->ip(),
                        'present_address' => $request->present_address,
                        'shipping_address' => $request->shipping_address,
                    ]);
                if(isset($updateUser)){
                    return response()->json([
                        'status' => 'success'
                    ]);
                }
            }
        }else{
            $updateUser = User::where('id',$request->id)->update([
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'email' => $request->email,
                    'phone_no' => $request->phone_no,
                    'ip_address'=>$request->ip(),
                    'present_address' => $request->present_address,
                    'shipping_address' => $request->shipping_address,
                ]);
            if(isset($updateUser)){
                return response()->json([
                    'status' => 'success'
                ]);
            }
        }

        return response()->json([
             $user
        ]);
    }
}
