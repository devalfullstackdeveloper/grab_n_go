<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Banner;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\MainCategory;
use App\Models\MasterCategory;
use App\Models\ExploreProductOffer;
use App\Models\ExploreProductOfferProduct;
use App\Models\Product;
use App\Models\Explore;
use App\Models\ExploreExploreCategory;
use App\Models\ProductsImage;
use App\Models\ProductMainCategory;
use Auth;

class HomeController extends Controller
{

	public function home(){

		/*get banner data*/

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

		/*get banner category data*/
		
		/*get explore data*/

		 $getExplore=Explore::select('id','explore_name','explore_details')->get()->toArray();
          $exploreProductData = array();
        

      foreach($getExplore as $key1 => $getExploreData)
      {
              $exploreData = array();
              $exploreData['explore_id'] = $getExploreData['id'];
              $exploreData['explore_name'] = $getExploreData['explore_name'];
              $exploreData['explore_details'] = $getExploreData['explore_details'];

              $getExploreDetails= ExploreExploreCategory::where('exploreexplorecategory.explore_id',$getExploreData['id'])
              ->get()->toArray();

              $categoryDataArray = array();              
              foreach($getExploreDetails as $key =>$exploreCategory){
                $exploreCategoryarray= array();
                $exploreCategoryarray['mastercategory_id']=$exploreCategory['mastercategory_id'];
                $exploreCategoryarray['maincategory_id']=$exploreCategory['maincategory_id'];
                $exploreCategoryarray['category_id']=$exploreCategory['category_id'];
                $exploreCategoryarray['subcategory_id']=$exploreCategory['subcategory_id'];

                if($exploreCategory['mastercategory_id']!=0 && $exploreCategory['maincategory_id']==0 && $exploreCategory['category_id']==0 && $exploreCategory['subcategory_id']==0){

                  $masterData = MasterCategory::where('id','=',$exploreCategory['mastercategory_id'])->select('master_category_name', 'master_category_image')->get()->toArray(); 
                  
                  $MasterCategoryData = array();
                  
                  $MasterCategoryData = array(
                    'master_category_name' =>$masterData[0]['master_category_name'],
                    'master_category_image' =>$baseUrl['base_url'].$masterData[0]['master_category_image'],
                  );
                  if(count($masterData) > 0){
                    $exploreCategoryarray['master_category_data']=$MasterCategoryData;
                  }

                }elseif($exploreCategory['mastercategory_id']!=0 && $exploreCategory['maincategory_id']!=0 && $exploreCategory['category_id']==0 && $exploreCategory['subcategory_id']==0){

                   $mainData = MainCategory::where('id','=',$exploreCategory['maincategory_id'])->select('main_category_name', 'main_category_image')->get()->toArray(); 

                   $MainCategoryData = array();
                  
                  $MainCategoryData = array(
                    'main_category_name' =>$mainData[0]['main_category_name'],
                    'main_category_image' =>$baseUrl['base_url'].$mainData[0]['main_category_image'],
                  );

                   if(count($mainData) > 0){
                    $exploreCategoryarray['main_category_data']=$MainCategoryData;
                  }

                }elseif($exploreCategory['mastercategory_id']!=0 && $exploreCategory['maincategory_id']!=0 && $exploreCategory['category_id']!=0 && $exploreCategory['subcategory_id']==0){

                   $categoryData = Category::where('id','=',$exploreCategory['category_id'])->select('category_name', 'category_image')->get()->toArray(); 

                  $CategoryData = array();
                  
                  $CategoryData = array(
                    'category_name' =>$categoryData[0]['category_name'],
                    'category_image' =>$baseUrl['base_url'].$categoryData[0]['category_image'],
                  );
                   if(count($categoryData) > 0){
                    $exploreCategoryarray['category_data']=$CategoryData;
                  }

                }elseif($exploreCategory['mastercategory_id']!=0 && $exploreCategory['maincategory_id']!=0 && $exploreCategory['category_id']!=0 && $exploreCategory['subcategory_id']!=0){

                   $subcategoryData = SubCategory::where('id','=',$exploreCategory['subcategory_id'])->select('sub_category_name', 'sub_category_image')->get()->toArray(); 

                    $SubCategoryData = array();
                  
                  $SubCategoryData = array(
                    'sub_category_name' =>$subcategoryData[0]['sub_category_name'],
                    'sub_category_image' =>$baseUrl['base_url'].$subcategoryData[0]['sub_category_image'],
                  );

                   if(count($subcategoryData) > 0){
                    $exploreCategoryarray['subcategory_data']=$SubCategoryData;
                  }

                }

                $categoryDataArray[] =$exploreCategoryarray;
              }
                 
              $exploreData['categories'] = $categoryDataArray;
              $exploreProductData[] = $exploreData;
     
      }

			
		/*get maincategory data*/

			$getMainCategory = MainCategory::select()->get();

			$mainCategory = array();

			foreach ($getMainCategory as $key => $value) {
				$mainCategory[] = array(
					'id' =>  $value->id,         
					'main_category_name' =>  $value->main_category_name,         
					'main_category_image' =>  $baseUrl['base_url'].$value->main_category_image,
					'status' =>  $value->status,
				);         
			}
		
		/*get maincategory wise product data*/

			$mainCategoryData = array();

			foreach ($getMainCategory as $key => $value) {

				$getMainProductCategory = ProductMainCategory::select('productsmaincategory.*','maincategory.main_category_name','products.*')
				->join('maincategory', 'maincategory.id', '=', 'productsmaincategory.maincategory_id')
				->join('products', 'products.id', '=', 'productsmaincategory.product_id')
				->where('productsmaincategory.maincategory_id', $value->id)
				->limit(5)
				->get()
				->toArray();

				$mainCategoryProductCount = ProductMainCategory::select('productsmaincategory.*','maincategory.main_category_name','products.*')
				->join('maincategory', 'maincategory.id', '=', 'productsmaincategory.maincategory_id')
				->join('products', 'products.id', '=', 'productsmaincategory.product_id')
				->where('productsmaincategory.maincategory_id', $value->id)
				->count();

				$productCategoryData = array();

					foreach ($getMainProductCategory as $getMainCategoryData) {

						$getProductImage = ProductsImage::select()->where('product_id',$getMainCategoryData['product_id'])->get()->toArray();

						if($getProductImage){
							$productCategoryData[] = array(
								"product_id" =>  $getMainCategoryData['product_id'],
								"product_name" =>  $getMainCategoryData['product_name'],
								"product_image" =>  $baseUrl['base_url'].$getProductImage[0]['product_image'],
								"product_price" =>  $getMainCategoryData['product_price'],
								"sale" => $getMainCategoryData['sale'],
								"sale_price" => $getMainCategoryData['sale_price'],
								"quantity" => $getMainCategoryData['quantity'],
							);                        
						}
					}
			if($mainCategoryProductCount){
				$mainCategoryData[] = array(
					"main_category_name" => $value->main_category_name,
					"main_category_id" => $value->id,
					"main_category_product_count" => $mainCategoryProductCount,
					"product" => $productCategoryData,
				);
			 }
			}



		/*get offer product and product data*/

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

					$offerProductCount = ExploreProductOfferProduct::select('exploreproductofferproduct.*','products.*','exploreproductoffer.*')
					->join('products', 'products.id', '=', 'exploreproductofferproduct.product_id')
					->join('exploreproductoffer', 'exploreproductoffer.id', '=', 'exploreproductofferproduct.exploreproductoffer_id')
					->where('exploreproductofferproduct.exploreproductoffer_id', $value->id)
					->count();

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
						"offer_product_count" => $offerProductCount,
						"product" => $productData,
					);
			}
			   

			return response(['banner' => $data,
							 'explore product' => $exploreProductData,
							'maincategory' =>$mainCategory,
							'productmaincategory' => $mainCategoryData,
							'exploreproductoffer' => $ExploreProductOfferData,
							'message' => 'Successful',
							'status' => 200], 200);
	}
	
}
