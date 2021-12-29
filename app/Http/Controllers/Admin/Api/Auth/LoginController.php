<?php

namespace App\Http\Controllers\Admin\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\SiteSetting;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;

class LoginController extends Controller
{
    public function login(Request $request){
        $validatedData = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $siteSetting = SiteSetting::first();
        $user = User::where('email',$request->email)->where('user_role',uSER::USER_ROLE_ADMIN)->first();

        if(isset($user)){
            if(Hash::check($request->password,$user->password)){

                $http = new Client();

                $response = $http->post(url("oauth/token"), [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => $siteSetting->client_id,
                        'client_secret' => $siteSetting->client_secret,
                        'username' => $request->email,
                        'password' => $request->password,
                        'scope' => '',
                    ],
                ]);

                // return json_decode((string) $response->getBody(), true);

                return response()->json([

                    'status' => "success",
                    'data' => json_decode((string) $response->getBody(), true)
                ]);
            }
        }else{
            return response()->json([
                'status' => "error",
                'msg' => "Data not found"
            ]);
        }

        return response()->json([
            $siteSetting, $user
        ]);

    }
}
