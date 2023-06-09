<?php

namespace App\Http\Controllers\Api;

use id;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ExploreProductOffer;
use App\Models\ExploreProductOfferProduct;
use App\Models\SubCategory;
use App\Models\MainCategory;
use App\Models\MasterCategory;
use App\Models\Explore;
use App\Models\ExploreExploreCategory;
use App\Http\Controllers\Controller;
use App\Models\MasterMainCategory;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductsImage;
use Config;

class ExploreController extends Controller
{
    public function exploreProduct()
    {
        $baseUrl = \Config::get('baseurl');

        $dataReturn = array();

        $getExplore = Explore::select('id', 'explore_name', 'explore_details')->where('isActive', '1')->get()->toArray();

        foreach ($getExplore as $key1 => $getExploreData) {

            $data = array();

            $data['explore_id'] = $getExploreData['id'];
            $data['explore_name'] = $getExploreData['explore_name'];
            $data['explore_details'] = $getExploreData['explore_details'];

            $getExploreDetails = ExploreExploreCategory::select('exploreexplorecategory.id', 'exploreexplorecategory.explore_id', 'exploreexplorecategory.mastercategory_id',
                'exploreexplorecategory.maincategory_id', 'exploreexplorecategory.category_id', 'exploreexplorecategory.subcategory_id',
                'mastercategory.master_category_name', 'maincategory.main_category_name', 'category.category_name', 'subcategory.sub_category_name')
                ->leftJoin('mastercategory', 'mastercategory.id', '=', 'exploreexplorecategory.mastercategory_id')
                ->leftJoin('maincategory', 'maincategory.id', '=', 'exploreexplorecategory.maincategory_id')
                ->leftJoin('category', 'category.id', '=', 'exploreexplorecategory.category_id')
                ->leftJoin('subcategory', 'subcategory.id', '=', 'exploreexplorecategory.subcategory_id')
                ->where('exploreexplorecategory.explore_id', $getExploreData['id'])
                ->where('exploreexplorecategory.isActive', '1')
                ->get()->toArray();

            $allCategories = array();
            $inCrement = 0;

            //Condition for checking wether the category is in the table or not
            foreach ($getExploreDetails as $key => $getExplore) {
                $name = '';
                $image = '';
                $getData = '';

                if (isset($getExplore['mastercategory_id']) && $getExplore['mastercategory_id'] != 0 && $getExplore['mastercategory_id'] != '') {
                    $getData = '1';
                }
                if (isset($getExplore['maincategory_id']) && $getExplore['maincategory_id'] != 0 && $getExplore['maincategory_id'] != '') {
                    $getData = '2';
                }
                if (isset($getExplore['category_id']) && $getExplore['category_id'] != 0 && $getExplore['category_id'] != '') {
                    $getData = '3';
                }
                if (isset($getExplore['subcategory_id']) && $getExplore['subcategory_id'] != 0 && $getExplore['subcategory_id'] != '') {
                    $getData = '4';
                }

                if ($getData == "1") {
                    $masterData = MasterCategory::where('id', '=', $getExplore['mastercategory_id'])->where('isActive', '1')->get()->toArray();

                    $master_category_image = str_replace('\\', '/', $masterData[0]['master_category_image']);
                    $id = $masterData[0]['id'];
                    $name = $masterData[0]['master_category_name'];
                    $image = $baseUrl['base_url'] . $master_category_image;
                }

                {
                    if ($getData == "2") {
                        $mainData = MainCategory::where('id', '=', $getExplore['maincategory_id'])->where('isActive', '1')->get()->toArray();
                        $main_category_image = str_replace('\\', '/', $mainData[0]['main_category_image']);
                        $id = $mainData[0]['id'];
                        $name = $mainData[0]['main_category_name'];
                        $image = $baseUrl['base_url'] . $main_category_image;
                    }
                    if ($getData == "3") {
                        $categoryData = Category::where('id', '=', $getExplore['category_id'])->where('isActive', '1')->get()->toArray();
                        $category_image = str_replace('\\', '/', $categoryData[0]['category_image']);
                        $id = $categoryData[0]['id'];
                        $name = $categoryData[0]['category_name'];
                        $image = $baseUrl['base_url'] . $category_image;

                    }
                    if ($getData == "4") {
                        $subCategoryData = SubCategory::where('id', '=', $getExplore['subcategory_id'])->where('isActive', '1')->get()->toArray();
                        $sub_category_image = str_replace('\\', '/', $subCategoryData[0]['sub_category_image']);
                        $id = $subCategoryData[0]['id'];
                        $name = $subCategoryData[0]['sub_category_name'];
                        $image = $baseUrl['base_url'] . $sub_category_image;
                    }

                }

                $allCategories[$inCrement]['id'] = $id;
                $allCategories[$inCrement]['name'] = $name;
                $allCategories[$inCrement]['image'] = $image;
                $inCrement++;

            }
            $data['category:'] = $allCategories;
            $dataReturn[] = $data;
        }

        return response([
            'explore_product' => $dataReturn,
            'messagecode' => 1,
            'message' => 'List of all product.'],
            200);
    }

    /*get offer all products and products data*/
    public function exploreAllProductsOffer(Request $request)
    {
        $baseUrl = \Config::get('baseurl');

        if (isset($request->offer_id)) {

            $getExploreAllProductsOffer = ExploreProductOffer::where('id', $request->offer_id)->where('status', 1)->where('isActive', '1')->first();
    
            if (isset($getExploreAllProductsOffer)) {

                $getExploreProductOfferProduct = ExploreProductOfferProduct::select('exploreproductofferproduct.*', 'products.*', 'exploreproductoffer.*')
                    ->join('products', 'products.id', '=', 'exploreproductofferproduct.product_id')
                    ->join('exploreproductoffer', 'exploreproductoffer.id', '=', 'exploreproductofferproduct.exploreproductoffer_id')
                    ->where('exploreproductofferproduct.exploreproductoffer_id', $request->offer_id)
                    ->get()
                    ->toArray();
    
                $offerProductCount = ExploreProductOfferProduct::select('exploreproductofferproduct.*', 'products.*', 'exploreproductoffer.*')
                    ->join('products', 'products.id', '=', 'exploreproductofferproduct.product_id')
                    ->join('exploreproductoffer', 'exploreproductoffer.id', '=', 'exploreproductofferproduct.exploreproductoffer_id')
                    ->where('exploreproductofferproduct.exploreproductoffer_id', $request->offer_id)
                    ->count();
    
                $productData = [];
                foreach ($getExploreProductOfferProduct as $getProductData) {
                    $productImage = ProductsImage::where('product_id', $getProductData['product_id'])->get()->toArray();
    
                    if (!empty($productImage)) {
                        $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                        $productData[] = [
                            "product_id" => $getProductData['product_id'],
                            "product_name" => $getProductData['product_name'],
                            "product_price" => $getProductData['product_price'],
                            "sale" => $getProductData['sale'],
                            "sale_price" => isset($getProductData['sale_price']) ? $getProductData['sale_price'] : null,
                            "packet" => isset($getProductData['packet']) ? $getProductData['packet'] : null,
                            "quantity" => $getProductData['quantity'],
                            "product_image" => $baseUrl['base_url'] . $product_image,
                        ];
                    }
                }
    
                $exploreAllProductsOfferData = [
                    "offer_product_name" => $getExploreAllProductsOffer->offer_product_name,
                    "offer_product_detail" => $getExploreAllProductsOffer->offer_product_detail,
                    "offer_id" => $getExploreAllProductsOffer->id,
                    "offer_product_count" => $offerProductCount,
                    "product" => $productData,
                ];
                return response([
                    'exploreallproductsoffer' => $exploreAllProductsOfferData,
                    'message' => 'Successful',
                    'status' => 200], 200);
            } else {
                return response([
                    'message' => 'No Data Found'],
                    200);
            }
        } else {
            $validation = Validator::make($request->all(), [
                'offer_id' => 'required',
            ]);
            if ($validation->fails()) {
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;
            }
        }
    }
}
?>