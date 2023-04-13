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
                'product_image' =>$productImageData,
                
            );
            }
            

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
                        'product_quantity' => $productviewdata['quantity'],
                        'sale' => $productviewdata['sale'],
                        'sale_price' => $productviewdata['sale_price'],
                    );      
                }
            $returnArr = array(
                "success" => true,
                "messagecode" => 1,
                "message" => "Your product details.",   
            );

            if(count($productData) > 0){
                $returnArr['product'] = $productData;
            }
            if(count($productViews) > 0){
                $returnArr['new_producto'] = $productViews;
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