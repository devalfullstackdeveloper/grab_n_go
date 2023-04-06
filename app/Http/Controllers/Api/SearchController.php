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
			$product = Product::select()->where('product_name', 'like', '%' . $request->search . '%')->get()->toArray();
			
			
			$productData = array();

			foreach($product as $products){

				$productImage = ProductsImage::select()->where('product_id',$products['id'])->get()->toArray();
				
				if($productImage){
					$productData[] = array(
					'product_id' =>$products['id'],
					'product_name' =>$products['product_name'],
					'product_image' =>$baseUrl['base_url'].$productImage[0]['product_image'],
					'product_price' =>$products['product_price'],
					'sale_price' =>isset($products['sale_price']) ? $products['sale_price'] : '0',
					'sale' =>$products['sale'],
					'quantity' =>$products['quantity'],
				);
				}
				

			}

			$data = array();
			$MasterCategoryData = array();
			$MainCategoryData = array();
			$CategoryData = array();
			$SubCategoryData = array();
			
			$masterCategory = MasterCategory::select()->where('master_category_name', 'like', '%' . $request->search . '%')->get()->toArray();

			foreach($masterCategory as $mastercategory){
			$MasterCategoryData[] = array(
					'mastercategory_id' =>$mastercategory['id'],
					'master_category_name' =>$mastercategory['master_category_name'],
					'master_category_image' =>$baseUrl['base_url'].$mastercategory['master_category_image'],
				);
			}

			$mainCategory = MainCategory::select()->where('main_category_name', 'like', '%' . $request->search . '%')->get()->toArray();

			foreach($mainCategory as $maincategory){
			$MainCategoryData[] = array(
					'maincategory_id' =>$maincategory['id'],
					'main_category_name' =>$maincategory['main_category_name'],
					'main_category_image' =>$baseUrl['base_url'].$maincategory['main_category_image'],
				);
			}

			$category = Category::select()->where('category_name', 'like', '%' . $request->search . '%')->get()->toArray();

			foreach($category as $categories){
			$CategoryData[] = array(
					'category_id' =>$categories['id'],
					'category_name' =>$categories['category_name'],
					'category_image' =>$baseUrl['base_url'].$categories['category_image'],
				);
			}

			$subCategory = SubCategory::select()->where('sub_category_name', 'like', '%' . $request->search . '%')->get()->toArray();

			foreach($subCategory as $subcategory){
			$SubCategoryData[] = array(
					'subcategory_id' =>$subcategory['id'],
					'sub_category_name' =>$subcategory['sub_category_name'],
					'sub_category_image' =>$baseUrl['base_url'].$subcategory['sub_category_image'],
				);
			}
			
			if(count($productData) > 0){
				$data['product']=$productData;
			}
			if(count($masterCategory) > 0){
				$data['master_category']=$MasterCategoryData;
			}
			if(count($mainCategory) > 0){
				$data['main_category']=$MainCategoryData;
			}
			if(count($category) > 0){
				$data['category']=$CategoryData;
			}
			if(count($subCategory) > 0){
				$data['sub_category']=$SubCategoryData;
			}

			$countData = count($product)+count($masterCategory)+count($mainCategory)+count($category)+count($subCategory);

			if($data){
				return response()->json([
					"search_data" => $data,
					"succccess" => true,
					"messagecode" => 1,	
					"message" => $countData.' result for '.'"'.$request->search.'"',
				]);
			}else{
				return response()->json([
					"success" => true,
					"message" => "Data not Found",	
				]);	
			}
			
		}else{
			return response()->json([
					"success" => true,
					"message" => "Data not Found",	
				]);	
		}
   	 
	}
}
