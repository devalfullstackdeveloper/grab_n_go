<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\MasterCategory;
use App\Models\MainCategory;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductsImage;


class SearchController extends Controller
{
	public function SearchData(Request $request)
	{
		$baseUrl= \Config::get('baseurl');

		if($request->search)
		{
			$product = Product::select('products.id','products.product_name')->where('product_name', 'like', '%' . $request->search . '%')->get()->toArray();
			
			
			$productData = array();

			foreach($product as $products){

				$productImage = ProductsImage::select()->where('product_id',$products['id'])->get()->toArray();
				

				$productData[] = array(
					'product_id' =>$products['id'],
					'product_name' =>$products['product_name'],
					'product_image' =>$baseUrl['base_url'].$productImage[0]['product_image'],
				);

			}

			$data = array();
			
			$masterCategory = MasterCategory::select('id','master_category_name','master_category_image')->where('master_category_name', 'like', '%' . $request->search . '%')->get()->toArray();
			$mainCategory = MainCategory::select('id','main_category_name','main_category_image')->where('main_category_name', 'like', '%' . $request->search . '%')->get()->toArray();
			$category = Category::select('id','category_name','category_image')->where('category_name', 'like', '%' . $request->search . '%')->get();
			$subCategory = SubCategory::select('id','sub_category_name','sub_category_image')->where('sub_category_name', 'like', '%' . $request->search . '%')->get()->toArray();
			
			if(count($productData) > 0){
				$data['product']=$productData;
			}
			if(count($masterCategory) > 0){
				$data['master_category']=$masterCategory;
			}
			if(count($mainCategory) > 0){
				$data['main_category']=$mainCategory;
			}
			if(count($category) > 0){
				$data['category']=$category;
			}
			if(count($subCategory) > 0){
				$data['sub_category']=$subCategory;
			}

			$countData = count($product)+count($masterCategory)+count($mainCategory)+count($category)+count($subCategory);
		// print_r($countData);
		// exit();

			if($data){
				return response()->json([
					"search_data" => $data,
					"succccess" => true,
					"message" => $countData.' result for '.'"'.$request->search.'"',
				]);
			}else{
				return response()->json([
					"success" => true,
					"message" => "Data not Found",	
					"messagecode" => 1,	
				]);	
			}
			
		}
   //  	 	
	}
}
