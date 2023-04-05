<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\ProductsImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function productDetail(Request $request)
    {     

        if(isset($request->product_id))
        {
            $baseUrl= \Config::get('baseurl');

            $productData=array();
            $product = Product::select()->where('id',$request->product_id)->first();

            $productImage = ProductsImage::select()->where('product_id',$request->product_id)->get();

            $productData = array(
                'product_id' =>$request->product_id,
                'product_name' =>$product->product_name,
                'product_details' =>$product->product_details,
                'product_image' =>$baseUrl['base_url'].$productImage[0]['product_image'],
                'product_price' =>$product->product_price,
                'quantity' =>$product->quantity,
                'sale' =>$product->sale,
                'sale_price' =>isset($product->sale_price) ? $product->sale_price : '0',
            );

            return response()->json([
                "product" => $productData,
                "success" => true,
                "messagecode" => 1,
                "message" => "Your product details.",   
            ]);


        }else{
            $validator = Validator::make($request->all(), [
                'product_id' => 'required',
            ]);

            if($validator->fails()){
                return response(['error' => $validator->errors(), 
                    'Validation Error']);
            }
        }
    }
}

?>