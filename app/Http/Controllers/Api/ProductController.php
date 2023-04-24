<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\ProductsImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;

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
            $product = Product::select()->where('id',$request->product_id)->where('status',1)->first();

            $productImage = ProductsImage::select()->where('product_id',$request->product_id)->get();

            $productImageData = array();
            foreach ($productImage as $key => $value) {
             
                $productImageData[] = $baseUrl['base_url'].$value->product_image;
                
            }
            if(isset($product)) {
                $productData = array(
                'product_id' =>$request->product_id,
                'product_name' =>$product->product_name,
                'product_details' =>$product->product_details,
                'product_price' =>$product->product_price,
                'quantity' =>$product->quantity,
                'sale' =>$product->sale,
                'sale_price' =>isset($product->sale_price) ? $product->sale_price : null,
                'packet' =>isset($product->packet) ? $product->packet : null,
                'product_image' =>$productImageData,
                
            );
            }
            
            //Show Buy it Again Products Data
            $userId = Auth::user()->id;

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
                            'quantity' => $productDataValue[0]['quantity'],
                            'points' => $productDataValue[0]['point'],
                            'sale' =>$productDataValue[0]['sale'],
                            'sale_price' =>$productDataValue[0]['sale_price'],
                            'packet' =>$productDataValue[0]['packet'],
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
                        'quantity' => $randomProductData['quantity'],
                        'sale' => $randomProductData['sale'],
                        'sale_price' => $randomProductData['sale_price'],
                        'packet' => $randomProductData['packet'],
                    );      
                }
        

            //Show New Products Data
            $productViewData = Product::latest()->where('status',1)->take(10)->get()->toArray();
        
                $productViews = array();
                
                foreach ($productViewData as $productviewdata) {

                    $productViewImage = ProductsImage::select()->where('product_id',$productviewdata['id'])->get()->toArray();

                    $productViews[] = array(
                        'product_id' =>$productviewdata['id'],
                        'product_name' =>$productviewdata['product_name'],
                        'product_price' =>$productviewdata['product_price'],
                        'product_image' =>$baseUrl['base_url'].$productViewImage[0]['product_image'],
                        'product_price' =>$productviewdata['product_price'],
                        'quantity' => $productviewdata['quantity'],
                        'sale' => $productviewdata['sale'],
                        'sale_price' => $productviewdata['sale_price'],
                        'packet' => $productviewdata['packet'],
                    );      
                }
            $returnArr = array(
                "success" => true,
                "messagecode" => 1,
                "message" => "Your product details.",   
            );

            if(count($productData) > 0){
                $returnArr['product_details'] = $productData;
            }
            if($product){
            $returnArr['buy_it_again'] = $product;
            }
            if(count($randomProductsViews) > 0){
                $returnArr['product_you_like'] = $randomProductsViews;
            }
            if(count($productViews) > 0){
                $returnArr['new_product'] = $productViews;
            }

            return response()->json($returnArr);

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