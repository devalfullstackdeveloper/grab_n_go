<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Banner;
use App\Models\MainCategory;
use App\Models\ExploreProductOffer;
use App\Models\ExploreProductOfferProduct;
use App\Models\Product;
use App\Models\ProductsImage;
use App\Models\ProductMainCategory;
use Auth;

class HomeController extends Controller
{

	public function home(){

		$getBanner = Banner::select()->get();
		$baseUrl= \Config::get('baseurl');

		$data = array();
		
		foreach ($getBanner as $key => $value) {
			$data[] = array(
				'id' =>  $value->id,         
				'banner_image' =>  $baseUrl['base_url'].$value->banner_image,         
				'banner_offer_type' =>  $value->banner_offer_type,
			);         
		}
		$getMainCategory = MainCategory::select()->get();

		$maincategory = array();

		foreach ($getMainCategory as $key => $value) {
			$maincategory[] = array(
				'id' =>  $value->id,         
				'main_category_name' =>  $value->main_category_name,         
				'main_category_image' =>  $baseUrl['base_url'].$value->main_category_image,
				'status' =>  $value->status,
			);         
		}
		
		$getdata= MainCategory::all();
           
            $mainCategoryData = array();
            
            foreach ($getdata as $key => $value) {
                
                $getMainProductCategory = ProductMainCategory::select('productsmaincategory.*','maincategory.main_category_name','products.*')
                ->join('maincategory', 'maincategory.id', '=', 'productsmaincategory.maincategory_id')
                ->join('products', 'products.id', '=', 'productsmaincategory.product_id')
                ->where('productsmaincategory.maincategory_id', $value->id)
                ->get()
                ->toArray();

                $productCategoryData = array();
                  
                foreach ($getMainProductCategory as $getMainCategoryData) {

                	$getProductImage = ProductsImage::select()->where('product_id',$getMainCategoryData['product_id'])
                	->get()
               		->toArray();

                    $productCategoryData[] = array(
                        "product_id" =>  $getMainCategoryData['id'],
                        "product_name" =>  $getMainCategoryData['product_name'],
                        "product_image" =>  $baseUrl['base_url'].$getProductImage[0]['product_image'],
                        "product_price" =>  $getMainCategoryData['product_price'],
                        "sale" => $getMainCategoryData['sale'],
                        "sale_price" => $getMainCategoryData['sale_price'],
                        "quantity" => $getMainCategoryData['quantity'],
                    );                        
                }

               		if(count($productCategoryData) != 0){
	                	$mainCategoryData[] = array(
	                    "main_category_name" => $value->main_category_name,
	                    "main_category_id" => $value->id,
	                    "product" => $productCategoryData,
               		);
                }
               
             }


		$getExploreProductOffer = ExploreProductOffer::select()->get();

		$ExploreProductOfferData = array();

		foreach ($getExploreProductOffer as $key => $value) {

			$getExploreProductOfferProduct = ExploreProductOfferProduct::select('exploreproductofferproduct.*','products.*','exploreproductoffer.*')
			->join('products', 'products.id', '=', 'exploreproductofferproduct.product_id')
			->join('exploreproductoffer', 'exploreproductoffer.id', '=', 'exploreproductofferproduct.exploreproductoffer_id')
			->where('exploreproductofferproduct.exploreproductoffer_id', $value->id)
			->limit(5)
			->get()
			->toArray();

			$productData = array();


			foreach ($getExploreProductOfferProduct as $getProductData) {

				$productImage = ProductsImage::select()->where('product_id',$getProductData['product_id'])->get()->toArray();
				
				$productData[] = array(
					"product_id" =>  $getProductData['product_id'],
					"product_name" => $getProductData['product_name'],
					"product_price" => $getProductData['product_price'],
					"sale" => $getProductData['sale'],
					"sale_price" => $getProductData['sale_price'],
					"quantity" => $getProductData['quantity'],
					"product_image" => $baseUrl['base_url'].$productImage[0]['product_image'],
				);                        
			}
			
			$ExploreProductOfferData[] = array(
				"offer_product_name" => $value->offer_product_name,
				"offer_product_detail" => $value->offer_product_detail,
				"offer_id" => $value->id,
				"product" => $productData,
			);
		}


		return response(['banner' => $data, 'maincategory' =>$maincategory,'productmaincategory' => $mainCategoryData,'exploreproductoffer' => $ExploreProductOfferData,
			'message' => 'Successful',
			'status' => 200], 200);
	}
	
}