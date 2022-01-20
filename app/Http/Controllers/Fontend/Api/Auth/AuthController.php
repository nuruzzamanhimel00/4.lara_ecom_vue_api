<?php

namespace App\Http\Controllers\Fontend\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Image;

class AuthController extends Controller
{
    public function registration(Request $request){
        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'image' => 'required',
            'phone_no' => 'required',
            'present_address' => 'required',
            'shipping_address' => 'required',
        ]);

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

            $createuser = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->firstname.rand(1,1000),
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'image' => $imagename,
                'phone_no' => $request->phone_no,
                'userStatusId' => 1, // active
                'ip_address'=>$request->ip(),
                'present_address' => $request->present_address,
                'shipping_address' => $request->shipping_address,
                'user_role' => 2, // 1=admin,2=user

            ]);
            if(isset($createuser)){
                return response()->json([
                    'status' => 'success'
                ]);
            }
        }

        return response()->json([
            $request->all()
        ]);

    }
}
