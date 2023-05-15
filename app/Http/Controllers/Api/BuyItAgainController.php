<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductsImage;
use Auth;

class BuyItAgainController extends Controller
{
	public function buyItAgain(Request $request){

		$userId = Auth::user()->id;
				
		$baseUrl= \Config::get('baseurl');

			$cartData = Cart::select('cart_product.product_id')->distinct()
			->join('cart_product','cart_product.cart_id','=','cart.id')
			->where('status',2)
			->where('user_id',$userId)
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
							'quantity' => $productDataValue[0]['quantity'],
							'points' => $productDataValue[0]['point'],
	                        'sale' =>$productDataValue[0]['sale'],
	                        'sale_price' =>$productDataValue[0]['sale_price'],
	                        'packet' =>$productDataValue[0]['packet'],
						);
			}
			$returnArr = array(
			"success" => true,
			);

        if ($product) {
            $returnArr['buy_it_again'] = $product;
        } else {
          $returnArr = array(
            "success" => false,
            "message" => "Products Not Found.",
        );
        }

			return response()->json($returnArr);
	}
}