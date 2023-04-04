<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartProduct;
use Auth;

class CheckOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {  

        $userId = Auth::user()->id;

        if($userId){

            $getUser = User::select()->where('id',$userId)->get();

            $getAddress = Address::select()->where('user_id',$userId)->where('default_address','1')->get();

            $getCart = Cart::select()->where('user_id',$userId)->get();
            
            foreach ($getCart as $key => $value) {

                $cartProductData = CartProduct::select()->where('cart_id',$value->id)->get();

                foreach ($cartProductData as $key => $getCartProductData) {
                    $cartdata = Cart::select()
                    ->join('cart_product','cart_product.cart_id' ,'=' ,'cart.id')
                    ->join('products','products.id' ,'=' ,'cart_product.product_id')
                    ->join('productsimage','productsimage.product_id' ,'=' ,'products.id')
                    ->where('user_id',$userId)
                    ->get();

                    $data = array();
                    $total = 0;
                    foreach ($cartdata as $key => $value) {
                        $total += $value->product_price * $value->product_quantity;
                        $data['sub_total'] = $total;
                        $data['delivery_fee'] = 0;
                        $data['small_order_fee'] = 0;
                        $data['tax'] = 0;
                        $data['tip'] = 0;
                        $data['order_total'] = $total;
                    }
                }
            }
            
            $userData = array(
                'user_name' => $getUser[0]['first_name'].' '.$getUser[0]['last_name'],
                'user_mobile_no' => $getUser[0]['mobile_no'],
                'lat' => $getAddress[0]['lat'],
                'long' => $getAddress[0]['long'],
                'product_sub_total' =>$data,
            );

            return response([
                'user_data' => $userData,
                'success' => true,
                'messagecode'=> '1',
                'message'=> 'Success']
                ,200);
        }
    }
}
