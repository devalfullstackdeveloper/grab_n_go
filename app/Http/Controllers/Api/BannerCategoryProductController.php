<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Banner;
use App\Models\ProductMainCategory;
use App\Models\Product;

class BannerCategoryProductController extends Controller
{

	public function bannerCategoryProduct(Request $request){


		if($request->banner_id) {

			//$bannerData = Banner::select()->where('id',$request->banner_id)->first();
			
			$bannerCategoyData = Banner::select('banners.*','mastercategory.*','mastermaincategory.*')
			->join('mastercategory', 'mastercategory.id', '=', 'banners.mastercategory_id')
			->join('mastermaincategory', 'mastermaincategory.mastercategory_id', '=', 'mastercategory.id')
			->where('banners.id',$request->banner_id)
			->get()
			->toArray();

			foreach($bannerCategoyData as $bannerCategoyProductData){

				$productData = ProductMainCategory::select()->where('maincategory_id',$bannerCategoyProductData['maincategory_id'])->get()->toArray();

				if(!empty($productData)){
					foreach($productData as $productDataName){

						$product = Product::select()->where('id',$productDataName['product_id'])->get()->toArray();


						echo "<pre>";
						print_r($product);
						echo "</pre>";

					}
				}
				exit();
				
			}
     
			exit();


			$bannerProductData = array();
			
			$bannerProductData = array(
				'banner_title' => $bannerData->banner_name,
				'banner_image' => $bannerData->banner_image
			);

			return response(['banner_data' => $bannerProductData,
				'message' => 'Successful',
				'status' => 200], 200);
			
		}else{
			$validation = Validator::make($request->all(), [
				'banner_id' => 'required',
			]);

			if($validation->fails()){
				$fieldsWithErrorMessagesArray = $validation->messages()->get('*');
				return $fieldsWithErrorMessagesArray;
			} 
		}
		

	}

}

?>