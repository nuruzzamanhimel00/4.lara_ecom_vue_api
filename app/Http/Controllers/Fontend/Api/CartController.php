<?php

namespace App\Http\Controllers\Fontend\Api;

use App\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Paymentmethod;
use App\SiteSetting;
use App\Order;
use DB;

class CartController extends Controller
{
    public function addCart(Request $request){
        $productid = $request->productid;
        $userid = $request->userid;
        $ip = \Request::ip();

        if(is_null($userid)){
            $cart = Cart::where('productid',$productid)
            ->where('ip_address',$ip)
            ->whereNull('order_id')
            ->first();
            if(!is_null($cart)){
                $cartUpdate = $cart->update([
                    'product_quantity' => $cart->product_quantity + 1
                ]);
                if($cartUpdate){
                    return response()->json([
                        'status' => 'success'
                    ]);
                }
            }else{
                $cartCreate = Cart::create([
                    'productid' => $productid,
                    'ip_address' => $ip,
                    'product_quantity' => 1,
                ]);
                if($cartCreate){
                    return response()->json([
                        'status' => 'success'
                    ]);
                }

            }
        }else{
            $cart = Cart::where('productid',$productid)
            ->where('userid',$userid)
            ->whereNull('order_id')
            ->first();
            if(!is_null($cart)){
                $cartUpdate = $cart->update([
                    'product_quantity' => $cart->product_quantity + 1
                ]);
                if($cartUpdate){
                    return response()->json([
                        'status' => 'success'
                    ]);
                }
            }else{
                $cartCreate = Cart::create([
                    'userid' => $userid,
                    'productid' => $productid,
                    'ip_address' => $ip,
                    'product_quantity' => 1,
                ]);
                if($cartCreate){
                    return response()->json([
                        'status' => 'success'
                    ]);
                }
            }

        }
        //     return response()->json([
        //         $request->all()
        //    ]);
    }

    public function fetchCart(Request $request){

        // return response()->json([
        //     'status' => 'success',
        //     'data' =>$request->all()
        // ]);

        $userid  = $request->userid;
        // $productid  = $request->productid;
        $ip = \Request::ip();

        if(!is_null($userid)){

            $countCarts = Cart::with(['productImages','product'])->where('userid',$userid)
            ->whereNull('order_id')
            ->get();
        }else{
            $countCarts = Cart::with(['productImages','product'])->where('ip_address',$ip)
            ->whereNull('order_id')
            ->get();
        }

        return response()->json([
            'status' => 'success',
            'data' =>$countCarts
        ]);
    }

    public function updateCartQuantity(Request $request){

        if($request->quantity <= 0){
            return response()->json([
                'status' => 'error',
            ]);
        }

        $updateCartQuantity = Cart::where('id',$request->id)->update([
            'product_quantity' => $request->quantity
        ]);
        if($updateCartQuantity){
            return response()->json([
                'status' => 'success',
            ]);
        }

    }

    public function deleteCart($id){
        $deleteCart = Cart::where('id',$id)->delete();
        if($deleteCart){
            return response()->json([
                'status' => 'success',
            ]);
        }
    }

    public function fetchSiteSetting(){

        $siteSetting = SiteSetting::first();
        if($siteSetting){
            return response()->json([
                'status' => 'success',
                'data' => $siteSetting
            ]);
        }
    }

    public function paymentModes(){
        $paymentModes = Paymentmethod::get();
        if($paymentModes){
            return response()->json([
                'status' => 'success',
                'data' => $paymentModes
            ]);
        }
    }

    public function orderSubmit(Request $request){
        $validatedData = $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'shipping_address' => 'required',
            'paymentid' => 'required',
        ]);

        $ip = $request->ip();

        if($request->paymentid != 1){

            $orderInsert = DB::table('orders')->insertGetId([
                'userid' => !is_null($request->userid) ? $request->userid : null,
                'paymentid' => $request->paymentid,
                'ip_address' => $ip,
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'shipping_address' => $request->shipping_address,
                'email' => $request->email,
                'message' => isset($request->message) ? $request->message : null,
                'is_paid' => 1,
                'is_complete' => 0,
                'is_seen_by_admin' => 0,
                'transation_id' => !is_null($request->transation_id) ? $request->transation_id : null,
                'shipping_cost' => $request->shipping_cost,
            ]);



        }else{
            $orderInsert = DB::table('orders')->insertGetId([
                'userid' => !is_null($request->userid) ? $request->userid : null,
                'paymentid' => $request->paymentid,
                'ip_address' => $ip,
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'shipping_address' => $request->shipping_address,
                'email' => $request->email,
                'message' => isset($request->message) ? $request->message : null,
                'is_paid' => 1,
                'is_complete' => 0,
                'is_seen_by_admin' => 0,
                // 'transation_id' => !is_null($request->transation_id) ? $request->transation_id : null,
                'shipping_cost' => $request->shipping_cost,
            ]);

        }

        $userid  = $request->userid;
        // $productid  = $request->productid;
        $ip = \Request::ip();

        if(!is_null($userid)){

            $cart = Cart::where('userid',$userid)
            ->whereNull('order_id')
            ->update([
                'order_id' => $orderInsert
            ]);
        }else{
            $cart = Cart::where('ip_address',$ip)
            ->whereNull('order_id')
            ->update([
                'order_id' => $orderInsert
            ]);
        }

        if($cart){
            return response()->json([
                'status' => 'success',
                // 'data' => $request->all()
            ]);
        }

    }




}
