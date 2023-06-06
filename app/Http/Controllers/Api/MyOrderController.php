<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartProduct;
use App\Models\ProductsImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class MyOrderController extends Controller
{
    public function myOrder(Request $request)
    {
        $userId = Auth::user()->id;

        if ($request->day || $request->month || $request->year) {

            $baseUrl = \Config::get('baseurl');

            /*get pending products data*/
            $cartAndProductData = Order::select('order.id', 'order.cart_id', 'order.user_id', 'order.status', 'order.created_at', 'order.updated_at', DB::raw('count(*) as total'))
                ->join('cart_product', 'cart_product.cart_id', '=', 'order.cart_id')
                ->where('order.user_id', $userId)
                ->groupBy('order.id', 'order.cart_id', 'order.user_id', 'order.status', 'order.created_at', 'order.updated_at')
                ->where('order.status', 1);

            /*get delivered products data*/
            $cartAndProduct = Order::select('order.id', 'order.cart_id', 'order.user_id', 'order.status', 'order.created_at', 'order.updated_at', DB::raw('count(*) as total'))
                ->join('cart_product', 'cart_product.cart_id', '=', 'order.cart_id')
                ->where('order.user_id', $userId)
                ->groupBy('order.id', 'order.cart_id', 'order.user_id', 'order.status', 'order.created_at', 'order.updated_at')
                ->where('order.status', 2);

            if ($request->day) {
                $date = \Carbon\Carbon::now()->subDays($request->day)->endOfDay();
                $cartAndProduct->where('order.created_at', '>=', $date);
                $cartAndProductData->where('order.created_at', '>=', $date);
            }

            if ($request->month) {
                $month = \Carbon\Carbon::now()->subMonths($request->month);
                $cartAndProduct->where('order.created_at', '>=', $month->format('m'));
                $cartAndProductData->where('order.created_at', '>=', $month->format('m'));
            }

            if ($request->year) {
                $cartAndProduct->whereYear('order.created_at', $request->year);
                $cartAndProductData->whereYear('order.created_at', $request->year);
            }

            $orderPendingData = $cartAndProductData->get()->toArray();
            $orderDeliveredData = $cartAndProduct->get()->toArray();

            $pendingData = [];

            foreach ($orderPendingData as $key => $orderDetails) {

                $pendingProductDetail = Order::select('cart_product.product_id','productsimage.product_image')
                    ->join('cart_product', 'cart_product.cart_id', '=', 'order.cart_id')
                    ->join('productsimage', 'productsimage.product_id', '=', 'cart_product.product_id')
                    ->where('order.id', $orderDetails['id'])
                    ->where('order.user_id', $userId)
                    ->where('order.status', 1)
                    ->get()->toArray();
  
                $pendingData[] = [
                    'order_id' => $orderDetails['id'],
                    'date' => $orderDetails['created_at'],
                    'total_product' => $orderDetails['total'],
                    'product_image' => $baseUrl['base_url'] . $pendingProductDetail[0]['product_image'],
                ];
            }

            $deliveredData = [];

            foreach ($orderDeliveredData as $key1 => $orderDetails) {

                $deleiveredProductDetail = Order::select('cart_product.product_id','productsimage.product_image')
                    ->join('cart_product', 'cart_product.cart_id', '=', 'order.cart_id')
                    ->join('productsimage', 'productsimage.product_id', '=', 'cart_product.product_id')
                    ->where('order.id', $orderDetails['id'])
                    ->where('order.user_id', $userId)
                    ->where('order.status', 2)
                    ->get()->toArray();
                   
                $deliveredData[] = [
                    'order_id' => $orderDetails['id'],
                    'date' => $orderDetails['created_at'],
                    'total_product' => $orderDetails['total'],
                    'product_image' => $baseUrl['base_url'] . $deleiveredProductDetail[0]['product_image'],
                ];
           
            }

            return response()->json([
                'success' => true,
                'messagecode' => "Successful",
                'pending_order' => $pendingData,
                'delivered_order' => $deliveredData
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'day' => 'required_without_all:month,year',
                'month' => 'required_without_all:day,year',
                'year' => 'required_without_all:day,month',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }
        }
    }

    public function pendingDeliveredOrder(Request $request)
    {
        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response([
                'error' => $validator->errors(),
                'Validation Error'
            ]);
        }


        $orderData = Order::select('order.id', 'cart_product.cart_id', 'cart_product.product_id', 'products.id', 'products.product_name', 'products.product_price', 'cart_product.product_quantity', 'order.status AS order_status', DB::raw('count(*) as total'))
                ->join('cart_product', 'cart_product.cart_id', '=', 'order.cart_id')
                ->join('products', 'products.id', '=', 'cart_product.product_id')
                ->where('order.id', $request->order_id)
                ->where('order.user_id', $userId)
                ->groupBy('order.id', 'order.status', 'cart_product.cart_id', 'cart_product.product_id', 'products.id', 'products.product_name', 'products.product_price', 'cart_product.product_quantity')
                ->get()->toArray();

            $productData = array();
            $data = array();
            $order_status = "";
            $totalProduct = 0;

            foreach ($orderData as $key => $getData) {
                $baseUrl = \Config::get('baseurl');
                $productImage = ProductsImage::select()->where('product_id', $getData['product_id'])->get()->toArray();
                $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                $productData[] = array( 
                    'product_id' => $getData['product_id'],                             
                    'product_name' => $getData['product_name'],
                    'product_price' => $getData['product_price'],
                    'product_image' => $baseUrl['base_url'] . $product_image,
                    'total_price' => $getData['product_price'] * (int) $getData['product_quantity'],                              
                );
        
                if ($getData['order_status'] == 1) {
                    $status = "pending";
                } else {
                    $status = "delivered";
                }

                $totalProduct += $getData['total'];
            }

            $total = 0;
            $totalPrice = array();

            foreach ($productData as $productDataValue) {

                $total += $productDataValue['total_price'];
                $totalPrice[] = $total;
                $data['sub_total'] = $total;
                $data['delivery_fee'] = 0;                          
                $data['tax'] = 0;                           
                $data['order_total'] = $total;
            }
          
                return response()->json([
                "success" => true,
                "messagecode" => "Successful",
                "status" => $status,
                "total_product" => $totalProduct,
                "product" => $productData,
                "product_sub_total" => $data,
                ]);
    }

    public function order(Request $request)
    {
            $userId = Auth::user()->id;
            if ($request->cart_id && $userId) {

                $cartExists = Cart::select('cart.*','order.cart_id')
                ->leftjoin('order', 'order.cart_id', '=', 'cart.id')
                ->where('cart.user_id',$userId)
                ->where('cart.status',2)
                ->where('cart.id',$request->cart_id)
                ->exists();

                if ($cartExists == '1') {

                   return response([
                    'success' => false,
                    'message' => 'cart_id already Exists.']
                    , 200);

               }else{

                $getCart = Cart::select()
                ->where('user_id',$userId)
                ->where('status',1)
                ->where('id',$request->cart_id)
                ->first();
                
                $statusUpdate = Cart::select()
                ->where('user_id',$userId)
                ->where('status',1)
                ->where('id',$request->cart_id)
                ->update(array("status" => 2));

                if(isset($getCart->id)){
                 $cart = Order::Create([
                    'cart_id' => $getCart->id,
                    'user_id' => $userId,                     
                ]);

                 return response([
                    'success' => true,
                    'messagecode' => '1',
                    'message' => 'Successfully create.']
                    , 200); 
             }else{

                return response([
                    'success' => false,
                    'message' => 'Invalid Id.']
                    , 200); 
            }

        }                         
    }else{
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required',   
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(),
                'Validation Error']);
        } 
    }
    }  
}
