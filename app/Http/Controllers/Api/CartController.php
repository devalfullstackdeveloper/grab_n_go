<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\ProductsImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;



class CartController extends Controller
{
	/*store the data in cart and cart product table*/
	public function storeCart(Request $request){

		$userId = Auth::user()->id;

	if($request->cart_id != ''){
	$validator = Validator::make($request->all(), [
			'cart_id' => 'required',	
			'product_quantity' => 'required',	

		]);

		if($validator->fails()){
			return response(['error' => $validator->errors(), 
				'Validation Error']);
		}

		if($request->product_quantity == '0'){

			CartProduct::select()->where('cart_id',$request->cart_id)->delete();

			return response()->json([
				"success" => true,
				"message" => "Your cart is empty",	
			]);
		} else {
			$productExist = CartProduct::where('cart_id', $request->cart_id)->where('product_id',$request->product_id)->exists();

			if($productExist == '1'){
			$UpdateProduct = CartProduct::where('cart_id', $request->cart_id)->update(array("product_quantity" => $request->product_quantity));
			return response()->json([
				"success" => true,
				"messageCode" => 1,
				"message" => "Your cart is updated.",	
				"cart_id" => $request->cart_id,
			]);

		}else{
		return response()->json([
				"success" => true,
				"message" => "Cart and products not found.",	
			]);
		}

			
		}
}else{
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

			$data = Cart::select()
			->join('cart_product','cart_product.cart_id' ,'=' ,'cart.id')
			->where('user_id',$userId)
			->where('product_id',$request->product_id)
			->exists();


			if($data == ''){

				$storeCartData = Cart::Create([
					'user_id' => $userId,
					'status' => isset($request->status) ? $request->status : '0',
				]);

				$storeCartProductData = CartProduct::Create([
					'cart_id' => $storeCartData->id,
					'product_id' => isset($request->product_id) ? $request->product_id : '',
					'product_quantity' => isset($request->product_quantity) ? $request->product_quantity : '',

				]);
			}else{
				return response()->json([
					"success" => true,
					"message" => "Product already Exist",
				]);
			}
			return response()->json([
				"success" => true,
				"messageCode" => 1,
				"cart_id" => $storeCartData->id
			]);

		}
}
		

	}

	/*show cart product data by cart_id*/
	public function showCart(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'cart_id' => 'required',	
		]);

		if($validator->fails()){
		return response(['error' => $validator->errors(), 
			'Validation Error']);
		}
		$cartdata = CartProduct::select()->where('cart_id',$request->cart_id)->first();
		$imagedata = ProductsImage::select()->where('product_id',$cartdata->product_id)->first();
		$baseUrl= \Config::get('baseurl');
		$storeproduct = Product::select()->where('id',$cartdata->product_id)->first();
		
		$data = array();
		$data['product_id'] = $storeproduct->id;
		$data['product_name'] = $storeproduct->product_name;
		$data['product_image'] =  $baseUrl['base_url'].$imagedata->product_image;
		$data['product_price'] = $storeproduct->product_price;
		$data['product_quantity'] = $cartdata->product_quantity;
		$data['total_price'] = $storeproduct->product_price*(int)$cartdata->product_quantity;
		$data['cart_id'] = $cartdata->id;
		
		return response()->json([
			"success" => true,
			"message" => "successfully",
			"data" => $data
		]);
	}

	
	
	/*get the cart product details by user_id*/
	public function userCart(Request $request)
	{

		$userId = Auth::user()->id;
		$cartData = Cart::select()->where('user_id',$userId)->get();
		$baseUrl= \Config::get('baseurl');

		foreach ($cartData as $key => $value) {
			$cartProductData = CartProduct::select()->where('cart_id',$value->id)->get();
	
		foreach ($cartProductData as $key => $getCartProductData) {
			$cartdata = Cart::select()
			->join('cart_product','cart_product.cart_id' ,'=' ,'cart.id')
			->join('products','products.id' ,'=' ,'cart_product.product_id')
			->join('productscategoryimage','productscategoryimage.product_id' ,'=' ,'products.id')
			->where('user_id',$userId)
			->get();

			$data = array();
			foreach ($cartdata as $key => $value) {

				$data[$key]['product_id'] = $value->product_id;
				$data[$key]['product_name'] = $value->product_name;
				$data[$key]['product_image'] = $baseUrl['base_url'].$value->product_image;
				$data[$key]['product_price'] = $value->product_price;
				$data[$key]['product_quantity'] = $value->product_quantity;
				$data[$key]['total_price'] = $value->product_price*(int)$value->product_quantity;
				$data[$key]['cart_id'] = $value->cart_id;
			}
			if($data){
			return response()->json([
				"success" => true,
				"message" => "successfully",
				"product_data" => $data
			]);		
			}
		}
			
		}

	}
}