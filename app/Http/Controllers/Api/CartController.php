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

		$userExists = Cart::where('user_id',$userId)->where('status',1)->exists();

		//if condition of user exists
		if($userExists == '1'){

			$cartExists = Cart::where('id',$request->cart_id)->exists();

			//if condition of cart exists
			if($cartExists == '1'){
				$productExists = CartProduct::select('cart_product.*','cart.*')
				->join('cart','cart.id' ,'=' ,'cart_product.cart_id')
				->where('product_id',$request->product_id)
				->where('cart.user_id',$userId)
				->where('cart.status',1)
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
					->where('cart.status',1)
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
						->where('cart.status',1)
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
				->where('cart.status',1)
				->exists();

				if($data == ''){

					if($request->product_id !=''){
						$storeCartData = Cart::Create([
							'user_id' => $userId,
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

		//Show Products Data By user_id
		$userId = Auth::user()->id;

		$baseUrl= \Config::get('baseurl');

		$cartAndProduct = Cart::select()
		->join('cart_product','cart_product.cart_id' ,'=' ,'cart.id')
		->join('products','products.id' ,'=' ,'cart_product.product_id')
		->where('user_id',$userId)
		->where('cart.status',1)
		->get()
		->toArray();

		$cartAndProductCount = Cart::select()
		->join('cart_product','cart_product.cart_id' ,'=' ,'cart.id')
		->join('products','products.id' ,'=' ,'cart_product.product_id')
		->where('user_id',$userId)
		->where('cart.status',1)
		->count();
		
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
				'packet' => $value['packet'],
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


		//Show Buy it Again Products Data
		$cartData = Cart::select('cart_product.product_id')->distinct()
			->join('cart_product','cart_product.cart_id','=','cart.id')
			->where('status',2)
			->where('user_id',$userId)
			->limit(10)
			->get()
			->toArray();

		$product = array();
		foreach ($cartData as $key => $value) {
			

				$productDataValue = Product::select()->where('id',$value['product_id'])->get()->toArray();

				$productImage = ProductsImage::select()->where('product_id',$productDataValue[0]['id'])->get()->toArray();



					$product[] = array(
						'product_id' =>$productDataValue[0]['id'],
						'product_name' =>$productDataValue[0]['product_name'],
						'product_price' =>$productDataValue[0]['product_price'],
						'product_image' =>$baseUrl['base_url'].$productImage[0]['product_image'],
						'product_price' =>$productDataValue[0]['product_price'],
						'product_quantity' => $productDataValue[0]['quantity'],
						'points' => $productDataValue[0]['point'],
                        'sale' =>$productDataValue[0]['sale'],
                        'sale_price' =>$productDataValue[0]['sale_price'],
                        'packet' =>$productDataValue[0]['packet'],
                        'sale_price' =>isset($productDataValue[0]->sale_price) ? $productDataValue[0]->sale_price : null,
					);
		}

		//Show Random Products Data
            $randomProducts = Product::inRandomOrder()->where('status',1)->limit(10)->get()->toArray();
    
                $randomProductsViews = array();
                
                foreach ($randomProducts as $randomProductData) {

                    $randomProductImage = ProductsImage::select()->where('product_id',$randomProductData['id'])->get()->toArray();

                    $randomProductsViews[] = array(
                        'product_id' =>$randomProductData['id'],
                        'product_name' =>$randomProductData['product_name'],
                        'product_price' =>$randomProductData['product_price'],
                        'product_image' =>$baseUrl['base_url'].$randomProductImage[0]['product_image'],
                        'product_price' =>$randomProductData['product_price'],
                        'product_quantity' => $randomProductData['quantity'],
                        'sale' => $randomProductData['sale'],
                        'sale_price' => $randomProductData['sale_price'],
                        'packet' => $randomProductData['packet'],
                    );      
                }
				
		//Show Latest 10 Products Data
		$productViewData = Product::latest()->where('status',1)->take(10)->get()->toArray();
		
		$newProducts = array();
		
		foreach ($productViewData as $productviewdata) {

			$productViewImage = ProductsImage::select()->where('product_id',$productviewdata['id'])->get()->toArray();

			$newProducts[] = array(
				'product_id' =>$productviewdata['id'],
				'product_name' =>$productviewdata['product_name'],
				'product_price' =>$productviewdata['product_price'],
				'product_image' =>$baseUrl['base_url'].$productViewImage[0]['product_image'],
				'product_price' =>$productviewdata['product_price'],
				'product_quantity' => $productviewdata['quantity'],
				'sale' => $productviewdata['sale'],
				'sale_price' => $productviewdata['sale_price'],
				'packet' => $productviewdata['packet'],
			);		
		}

		$returnArr = array(
			"success" => true,
		);

		if(count($productData) > 0){
			$returnArr['messagecode'] = 1;
			$returnArr['message'] = "Your product details.";
			$returnArr['cart_data'] = $productData;
			$returnArr['order_total'] = $total;
			$returnArr['product_count'] = $cartAndProductCount;
			$returnArr['cart_id'] =$cartAndProduct[0]['cart_id'];

		}else{
			$returnArr['messagecode'] = 0;
			$returnArr['message'] = "Your cart is empty.";
		}
		
		if($product){
			$returnArr['buy_it_again'] = $product;
		}
		if(count($randomProductsViews) > 0){
                $returnArr['product_you_like'] = $randomProductsViews;
        }
		if(count($newProducts) > 0){
			$returnArr['new_product'] = $newProducts;
		}
		

		return response()->json($returnArr);

	}
}