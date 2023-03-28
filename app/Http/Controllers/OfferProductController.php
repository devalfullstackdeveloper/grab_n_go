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

        return view('offerproduct.offerproduct');
    }

    public function create(){

        $product = Product::all();

        return view('offerproduct.offerproductadd',compact('product'));
    }
    
    public function allProduct(){
        $product = Product::select()->get();

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
}

?>