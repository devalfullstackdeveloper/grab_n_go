<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\ExploreProductOffer;
use App\Models\ExploreProductOfferProduct;

class OfferProductController extends Controller
{
    public function index(){

     $ExploreProductOffer = ExploreProductOffer::select()->orderBy('id','desc')->where('isActive','1')->get();
        return view('offerproduct.offerproduct',compact('ExploreProductOffer'));
    }

    public function create(){

        $product = Product::all();

        return view('offerproduct.offerproductadd',compact('product'));
    }
    
    public function allProduct(){
        $product = Product::select()->where('isActive','1')->where('status','1')->get();

        $data  = array();
        $getdata = array();

        foreach ($product as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['value'] = $value->product_name;  
        }

        $getdata['candidateItems'] = $data;

        return response()->json([
            'product' => $getdata
        ]);  
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "offer_product_name" => 'required|string',
            "offer_product_detail" =>  'required|string',
            'status' => 'required|in:1,2',
            "product_id" => "required",
        ]);

        $data = ExploreProductOffer::create([
            'offer_product_name' => $request->offer_product_name,
            'offer_product_detail' => $request->offer_product_detail,
            'status' => $request->status
        ]);

        $product_id = explode(',',$request->product_id);

        foreach ($product_id as $key => $value) {
                
            
            $storeExploreProductIdData = ExploreProductOfferProduct::create([
                'exploreproductoffer_id'=> $data->id,
                'product_id'=>$value,
            ]);
   }
               

 return redirect()->intended('offerproduct')->with('message','Data stored');

           
        
    }

    public function edit($id){

        $data = ExploreProductOffer::select()->where('id',$id)->first();
        return view('offerproduct.offerproductedit',compact('data'));
    }
    public function selectedProducts(Request $request)
    {
        $product = Product::select()->get();

        $data  = array();
        $selectProductData  = array();
        $getdata = array();

        foreach ($product as $key => $value) {

        $productData = ExploreProductOfferProduct::select('exploreproductofferproduct.*','products.*')
                ->join('products', 'products.id', '=', 'exploreproductofferproduct.product_id')
                ->where('exploreproductofferproduct.exploreproductoffer_id',$request->explore_offer_id)
                ->get()
                ->toArray();
                 foreach ($productData as $keys => $values) {
                     $selectProductData[$keys]['id'] = $values['product_id'];
                     $selectProductData[$keys]['value'] = $values['product_name'];
                 }
                     
            
            $data[$key]['id'] = $value->id;
            $data[$key]['value'] = $value->product_name;  
        }
            
        
        $getdata['candidateItems'] = array(

            'selectProductData' => $selectProductData,
            'allproduct' => $data
        );

        return response()->json([
            'product' => $getdata
        ]);  
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            "offer_product_name" => 'required|string',
            "offer_product_detail" =>  'required|string',
            'status' => 'required|in:1,2',
            "product_id" => "required",
        ]);

        $data = ExploreProductOffer::where('id', $request->id)->update([
            'offer_product_name' => isset($request->offer_product_name) ? $request->offer_product_name : '',
            'offer_product_detail' => isset($request->offer_product_detail) ? $request->offer_product_detail : '',
            'status' => isset($request->status) ? $request->status : ''
        ]);


    $deleteData = ExploreProductOfferProduct::select()->where('exploreproductoffer_id',$request->id)->delete();

     $product_id = explode(',',$request->product_id);

        foreach ($product_id as $key => $value) {
         $storeExploreProductIdData = ExploreProductOfferProduct::create([
                'exploreproductoffer_id'=> $request->id,
                'product_id'=>$value,
            ]);

        }
        
        return redirect()->intended('offerproduct')->with('message','Update the data');

    }


    public function delete(Request $request)
    {
        $UpdateDetails = ExploreProductOffer::where('id', $request->id)->update([
            "isActive" => ($request->isActive==1) ? 1 : 0,
        ]);
        return back();
    }
}

?>