<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;



class CartController extends Controller
{

	public function storecart(Request $request){

		$userId = Auth::user()->id;

		$validator = Validator::make($request->all(), [
			'product_id' => 'required',
			'product_quantity' => 'required'
		]);

		if($validator->fails()){
			return response(['error' => $validator->errors(), 
				'Validation Error']);
		}

		if($request->cart_id == ''){

			$validation = Validator::make($request->all(), [
				'product_id' => 'required',
				'product_quantity' => 'required'
			]);

			if($validation->fails()){
				$fieldsWithErrorMessagesArray = $validation->messages()->get('*');
				return $fieldsWithErrorMessagesArray;
			}

			$storeCartData = Cart::Create([
				'user_id' => $userId,
				'status' => isset($request->status) ? $request->status : '0',
			]); 

			$storeCartProductData = CartProduct::Create([
				'cart_id' => $storeCartData->id,
				'product_id' => isset($request->product_id) ? $request->product_id : '',
				'product_quantity' => isset($request->product_quantity) ? $request->product_quantity : '',
			]);

			return response()->json([
				"success" => true,
				"message" => "Cart is ready",
				"cart_id" => $storeCartData->id
			]);

		}else{

			$cartId = Cart::orderBy('id', 'DESC')->first();

			$data = CartProduct::where('cart_id', $request->cart_id)->where('product_id',$request->product_id)->exists();

			if($data != ''){

				return response()->json([
					"success" => true,
					"message" => "Cart product already Exist",
				]);


			}else{

				$storeCartProductData = CartProduct::Create([
					'cart_id' => $cartId->id,
					'product_id' => isset($request->product_id) ? $request->product_id : '',
					'product_quantity' => isset($request->product_quantity) ? $request->product_quantity : '',
				]);

				return response()->json([
					"success" => true,
					"message" => "successfully",

				]);
			}
		}

	}

	public function show(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'cart_id' => 'required',	
		]);

		if($validator->fails()){
		return response(['error' => $validator->errors(), 
			'Validation Error']);
		}
		$cartdata = CartProduct::select()->where('cart_id',$request->cart_id)->get()->toArray();
		
		return response()->json([
			"success" => true,
			"message" => "successfully",
			"data" => $cartdata
		]);
	}

	public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'cart_id' => 'required',	
			'product_quantity' => 'required',	

		]);

		if($validator->fails()){
		return response(['error' => $validator->errors(), 
			'Validation Error']);
		}

		if($request->product_quantity == '0'){

			CartProduct::select()->where('cart_id',$request->cart_product_id)->delete();

		return response()->json([
			"success" => true,
			"message" => "Your cart is empty",	
		]);
		} else {
		 	$Updateproduct = CartProduct::where('cart_id', $request->cart_product_id)->update(array("product_quantity" => $request->product_quantity));

			return response()->json([
				"success" => true,
				"message" => "updated successfully",
			]);
		}
	}

}