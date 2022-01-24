<?php

namespace App\Http\Controllers\Fontend\Api;

use App\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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




}
