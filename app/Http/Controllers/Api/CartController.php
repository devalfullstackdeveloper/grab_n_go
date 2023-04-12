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
	public function addToCart(Request $request){

		$userId = Auth::user()->id;
		$userExists = Cart::where('user_id',$userId)->exists();

		//if condition of user exists
		if($userExists == '1'){

			$cartExists = Cart::where('id',$request->cart_id)->exists();

			//if condition of cart exists
			if($cartExists == '1'){
				$productExists = CartProduct::select('cart_product.*','cart.*')
				->join('cart','cart.id' ,'=' ,'cart_product.cart_id')
				->where('product_id',$request->product_id)
				->where('cart.user_id',$userId)
				->exists();
				//if condition of product exists
				if($productExists != '1'){
					$storeCartProductData = CartProduct::Create([
						'cart_id' => isset($request->cart_id) ? $request->cart_id : '',
						'product_id' => isset($request->product_id) ? $request->product_id : '',
						'product_quantity' => isset($request->product_quantity) ? $request->product_quantity : '',

					]);
					return response()->json([
						"success" => true,
						"messagecode" => 1,
						"cart_id" => $storeCartProductData->cart_id						

					]);
				}else{
					$productExists = CartProduct::select('cart_product.*','cart.*')
					->join('cart','cart.id' ,'=' ,'cart_product.cart_id')
					->where('product_id',$request->product_id)
					->where('cart.user_id',$userId)
					->where('cart_product.product_quantity',$request->product_quantity)
					->exists();

					//if condition of delete product in cart
					if($request->product_quantity == '0'){
						CartProduct::select()
						->join('cart','cart.id' ,'=' ,'cart_product.cart_id')
						->where('product_id',$request->product_id)
						->where('cart.user_id',$userId)
						->delete();

						return response()->json([
							"success" => true,
							"messagecode" => 3,
							"message" => "Your product is deleted",	
						]);
					}

					//if condition of product update
					if($productExists != '1'){
						$UpdateProduct = CartProduct::select()
						->join('cart','cart.id' ,'=' ,'cart_product.cart_id')
						->where('product_id', $request->product_id)
						->where('cart.user_id',$userId)
						->update(array("product_quantity" => $request->product_quantity));
						return response()->json([
							"success" => true,
							"messagecode" => 2,
							"message" => "Your cart is updated.",	
							"cart_id" => $request->cart_id,
						]);

						
					}else{
						return response()->json([
							"success" => true,
							"message" => "Product already exists."

						]);
						
					}
				}
				
			}
			else{
				$validator = Validator::make($request->all(), [
					'product_id' => 'required',	
					'product_quantity' => 'required',	
				]);

				if($validator->fails()){
					return response(['error' => $validator->errors(), 
						'Validation Error']);
				}else{	
					return response()->json([
						"success" => true,
						"message" => "User cart already exists."
					]);		
				}
			}
		}
		else{

			//if condition of when cart_id blank then cart and cart product add in database
			if($request->cart_id == ''){

				$data = Cart::select('cart.*','cart_product.*')
				->join('cart_product','cart_product.cart_id' ,'=' ,'cart.id')
				->where('user_id',$userId)
				->where('product_id',$request->product_id)
				->exists();

				if($data == ''){

					if($request->product_id !=''){
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
							"messagecode" => 1,
							"cart_id" => $storeCartData->id
						]);
						
					}else{
						$validator = Validator::make($request->all(), [
							'product_id' => 'required',	
							'product_quantity' => 'required',	
						]);

						if($validator->fails()){
							return response(['error' => $validator->errors(), 
								'Validation Error']);
						}
					}

				}else{
					return response()->json([
						"success" => true,
						"message" => "Product already Exist",
					]);
				}
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
		$cartdata = CartProduct::select()->where('cart_id',$request->cart_id)->get()->toArray();
		$baseUrl= \Config::get('baseurl');
		$getProduct = Product::select()->where('id',$cartdata[0]['product_id'])->get()->toArray();

		$productData = array();
		foreach($getProduct as $product){

			$productImage = ProductsImage::select()->where('product_id',$product['id'])->get()->toArray();

			$productData[] = array(
				'product_id' =>$product['id'],
				'product_name' =>$product['product_name'],
				'product_image' =>$baseUrl['base_url'].$productImage[0]['product_image'],
				'product_price' =>$product['product_price'],
				'product_quantity' => $cartdata[0]['product_quantity'],
				'total_price' => $product['product_price']*(int)$cartdata[0]['product_quantity'],
				'cart_id' => $cartdata[0]['id'],
			);

		}
		
		return response()->json([
			"success" => true,
			"message" => "successfully",
			"data" => $productData
		]);
	}

	/*get the cart product details by user_id*/
	public function userCart(Request $request)
	{

		$userId = Auth::user()->id;
		$cartData = Cart::select()->where('user_id',$userId)->get();
		$baseUrl= \Config::get('baseurl');

		$cartAndProduct = Cart::select()
		->join('cart_product','cart_product.cart_id' ,'=' ,'cart.id')
		->join('products','products.id' ,'=' ,'cart_product.product_id')
		->where('user_id',$userId)
		->get()
		->toArray();

		$productData = array();
		
		foreach ($cartAndProduct as $key => $value) {

			$productImage = ProductsImage::select()->where('product_id',$value['product_id'])->get()->toArray();

			$productData[] = array(
				'product_id' =>$value['product_id'],
				'product_name' =>$value['product_name'],
				'product_price' =>$value['product_price'],
				'product_image' =>$baseUrl['base_url'].$productImage[0]['product_image'],
				'product_price' =>$value['product_price'],
				'product_quantity' => $value['product_quantity'],
				'total_price'=> $value['product_price']*(int)$value['product_quantity'],
				'cart_id'=> $value['cart_id'],
			);

		}
		
		$total = 0;
		$totalPrice = array();

		foreach($productData as $productDataValue){
			$total += $productDataValue['total_price'];
    		$totalPrice[] = $total;    	
		}

		if($productData){
			return response()->json([
				"success" => true,
				"message" => "successfully",
				"product_data" => $productData,
				"order_total" => $total
			]);		
		}else{
			return response()->json([
				"success" => true,
				"messagecode" => 1,
				"message" => "Empty Cart"
			]);		
		}
		

	}
}