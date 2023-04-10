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
use App\Models\MasterMainCategory;
use App\Models\MainCategoryCategory;
use App\Models\CategorySubCategory;
use App\Models\BannerCategory;
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
		        
        $bdata= BannerCategory::select('bannercategory.*','mastercategory.master_category_name','mastercategory.master_category_image','maincategory.main_category_name','maincategory.main_category_image','category.category_name','subcategory.sub_category_name','category.category_image','subcategory.sub_category_image')
        ->leftJoin('mastercategory', 'mastercategory.id', '=', 'bannercategory.mastercategory_id')
        ->leftJoin('maincategory', 'maincategory.id', '=', 'bannercategory.maincategory_id')
        ->leftJoin('category', 'category.id', '=', 'bannercategory.category_id')
        ->leftJoin('subcategory', 'subcategory.id', '=', 'bannercategory.subcategory_id')
        ->get()->toArray();

        $bannerCategoryData = array(); 
        $increment = 0;

        //Condition for checking wether the category is in the table or not
        foreach($bdata as $key=>$getExplore)
        {  
            $category='';
            $name = '';
            $image = '';
            $ids = '';
            if(isset($getExplore['mastercategory_id'])  && $getExplore['mastercategory_id'] != 0)
            {
                $category='master'; 
                $master_data = MasterCategory::where('id','=',$getExplore['mastercategory_id'])->get()->toArray();
                $data = array();
                
                $data = array(
                    "mastercategory_id" =>$getExplore['mastercategory_id'],
                    "master_category_name" =>$master_data[0]['master_category_name'],
                    "master_category_image" => $baseUrl['base_url'].$master_data[0]['master_category_image']
                );
            
            }
            if(isset($getExplore['maincategory_id'])  && $getExplore['maincategory_id'] != 0)
            {
                $category='main'; 
                $main_data = MasterMainCategory::select('mastermaincategory.*','maincategory.*')
                ->Join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
                ->where('mastermaincategory.maincategory_id','=',$getExplore['maincategory_id'])->get()->toArray();

                $data = array();
                
                $data = array(
                    "mastercategory_id" =>$main_data[0]['mastercategory_id'],
                    "maincategory_id" =>$main_data[0]['maincategory_id'],
                    "main_category_name" =>$main_data[0]['main_category_name'],
                    "main_category_image" => $baseUrl['base_url'].$main_data[0]['main_category_image']
                );
            }
            if(isset($getExplore['category_id'])  && $getExplore['category_id'] != 0)
            {
                $category='cat'; 
                $main_data = MainCategoryCategory::select('maincategorycategory.*','category.*')
                ->Join('category', 'category.id', '=', 'maincategorycategory.category_id')
                ->where('maincategorycategory.category_id','=',$getExplore['category_id'])->get()->toArray();

                $data = array();
                
                $data = array(
                    "mastercategory_id" =>$getExplore['mastercategory_id'],
                    "maincategory_id" =>$main_data[0]['maincategory_id'],
                    "category_id" =>$main_data[0]['category_id'],
                    "category_name" =>$main_data[0]['category_name'],
                    "category_image" => $baseUrl['base_url'].$main_data[0]['category_image']
                );
            }
            
            if(isset($getExplore['subcategory_id'])  && $getExplore['subcategory_id'] != 0)
            {
                $category='subcat'; 
                $main_data = CategorySubCategory::select('categorysubcategory.*','subcategory.*')
                ->Join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
                ->where('categorysubcategory.subcategory_id','=',$getExplore['subcategory_id'])->get()->toArray();

                $data = array();
                
                $data = array(
                    "mastercategory_id" =>$getExplore['mastercategory_id'],
                    "maincategory_id" =>$getExplore['maincategory_id'],
                    "category_id" =>$main_data[0]['category_id'],
                    "subcategory_id" =>$main_data[0]['subcategory_id'],
                    "sub_category_name" =>$main_data[0]['sub_category_name'],
                    "sub_category_image" => $baseUrl['base_url'].$main_data[0]['sub_category_image']
                );
            }
            $bannerCategoryData[$increment] = $data;
            $increment++;
        }

		/*get explore data*/

		 $getExplore=Explore::select('id','explore_name','explore_details')->get()->toArray();
          $exploreCategoryData = array();
        

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
                    'category_name' =>$masterData[0]['master_category_name'],
                    'category_image' =>$baseUrl['base_url'].$masterData[0]['master_category_image'],
                  );
                  if(count($masterData) > 0){
                    $exploreCategoryarray['category_data']=$MasterCategoryData;
                  }

                }elseif($exploreCategory['mastercategory_id']!=0 && $exploreCategory['maincategory_id']!=0 && $exploreCategory['category_id']==0 && $exploreCategory['subcategory_id']==0){

                   $mainData = MainCategory::where('id','=',$exploreCategory['maincategory_id'])->select('main_category_name', 'main_category_image')->get()->toArray(); 

                   $MainCategoryData = array();
                  
                  $MainCategoryData = array(
                    'category_name' =>$mainData[0]['main_category_name'],
                    'category_image' =>$baseUrl['base_url'].$mainData[0]['main_category_image'],
                  );

                   if(count($mainData) > 0){
                    $exploreCategoryarray['category_data']=$MainCategoryData;
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
                    'category_name' =>$subcategoryData[0]['sub_category_name'],
                    'category_image' =>$baseUrl['base_url'].$subcategoryData[0]['sub_category_image'],
                  );

                   if(count($subcategoryData) > 0){
                    $exploreCategoryarray['category_data']=$SubCategoryData;
                  }

                }

                $categoryDataArray[] =$exploreCategoryarray;
              }
                 
              $exploreData['categories'] = $categoryDataArray;
              $exploreCategoryData[] = $exploreData;
     
      }

		
		/*get category wise product data*/

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
							'bannercategory' => $bannerCategoryData,
							'explorecategory' => $exploreCategoryData,
							'exploreproductoffer' => $ExploreProductOfferData,
							'message' => 'Successful',
							'status' => 200], 200);
	}
	
}
