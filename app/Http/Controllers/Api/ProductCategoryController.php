<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategorySubCategory;
use App\Models\MainCategory;
use App\Models\MainCategoryCategory;
use App\Models\MasterCategory;
use App\Models\MasterMainCategory;
use App\Models\ProductAllCategory;
use App\Models\ProductsImage;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    public function productFromMaster(Request $request)
    {
        $baseUrl = \Config::get('baseurl');
        $data = array();
        if (isset($request->mastercategory_id)) {
            $getmasterDetails = MasterCategory::select()->where('mastercategory.id', $request->mastercategory_id)->where('status', 1)->where('isActive', '1')->first();

            if (isset($getmasterDetails)) {
                // $getmasterDetails=$getmasterDetails->toArray();
                $data['mastercategory_id'] = $getmasterDetails['id'];
                $data['mastercategory_name'] = $getmasterDetails['master_category_name'];

                /* Get Product From MaterCategory*/
                $getProductfromMasterCategory = ProductAllCategory::select('*')
                    ->join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('mastercategory_id', $request->mastercategory_id)
                    ->where('maincategory_id', 0)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->limit(10)
                    ->get()->toArray();

                $getProductfromMasterCategoryCount = ProductAllCategory::select()
                    ->join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('mastercategory_id', $request->mastercategory_id)
                    ->where('maincategory_id', 0)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->count();

                $data['product_count'] = $getProductfromMasterCategoryCount;

                foreach ($getProductfromMasterCategory as $getProductfromMasterCategoryData) {
                    $productImage = ProductsImage::select()->where('product_id', $getProductfromMasterCategoryData['id'])->get()->toArray();
                    $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                    $data['product'][] = array(
                        'product_id' => $getProductfromMasterCategoryData['id'],
                        'product_image' => $baseUrl['base_url'] . $product_image,
                        'product_name' => $getProductfromMasterCategoryData['product_name'],
                        'product_price' => $getProductfromMasterCategoryData['product_price'],
                        'point' => $getProductfromMasterCategoryData['point'],
                        'sale' => $getProductfromMasterCategoryData['sale'],
                        'sale_price' => isset($getProductfromMasterCategoryData['sale_price']) ? $getProductfromMasterCategoryData['sale_price'] : null,
                        'packet' => isset($getProductfromMasterCategoryData['packet']) ? $getProductfromMasterCategoryData['packet'] : null,
                        'quantity' => $getProductfromMasterCategoryData['quantity'],
                    );
                }

                /* Get Products From Maincategory */
                $getMasterMainDetails = MasterMainCategory::select('mastermaincategory.mastercategory_id', 'mastermaincategory.maincategory_id', 'maincategory.main_category_name')
                    ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
                    ->where('mastermaincategory.mastercategory_id', $request->mastercategory_id)
                    ->get()->toArray();

                foreach ($getMasterMainDetails as $key => $getMasterCategoryValue) {
                    $getProductDetails = ProductAllCategory::select('products_all_category.*', 'products.*')
                        ->join('products', 'products.id', '=', 'products_all_category.product_id')
                        ->where('products_all_category.maincategory_id', $getMasterCategoryValue['maincategory_id'])
                        ->where('products.status', 1)
                        ->where('products.isActive', '1')
                        ->limit(10)
                        ->get()->toArray();

                    $getProductCount = ProductAllCategory::select('products_all_category.*', 'products.*')
                        ->join('products', 'products.id', '=', 'products_all_category.product_id')
                        ->where('products_all_category.maincategory_id', $getMasterCategoryValue['maincategory_id'])
                        ->where('products.status', 1)
                        ->where('products.isActive', '1')
                        ->count();

                    if ($getProductCount) {
                        $getMasterCategoryValue['product_count'] = $getProductCount;
                    }

                    $mainCategoryArray = array();
                    if (count($getProductDetails) > 0) {
                        $mainCategoryArray = $getMasterCategoryValue;
                    } else {
                        continue;
                    }

                    $productMain = array();
                    foreach ($getProductDetails as $key1 => $getMasterCategoryValue1) {
                        $productImage = ProductsImage::select()->where('product_id', $getMasterCategoryValue1['id'])->get()->toArray();
                        $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                        $productMain[] = array(
                            'product_id' => $getMasterCategoryValue1['id'],
                            'product_image' => $baseUrl['base_url'] . $product_image,
                            'product_name' => $getMasterCategoryValue1['product_name'],
                            'product_price' => $getMasterCategoryValue1['product_price'],
                            'point' => $getMasterCategoryValue1['point'],
                            'sale' => $getMasterCategoryValue1['sale'],
                            'sale_price' => isset($getMasterCategoryValue1['sale_price']) ? $getMasterCategoryValue1['sale_price'] : null,
                            'packet' => isset($getMasterCategoryValue1['packet']) ? $getMasterCategoryValue1['packet'] : null,
                            'quantity' => $getMasterCategoryValue1['quantity'],
                        );
                    }
                    $mainCategoryArray['product'] = $productMain;
                    $data['main_category'][] = $mainCategoryArray;
                }

                return response([
                    'mastercategory' => $data,
                    'message' => 'List of all master category product.'],
                    200);
            } else {
                return response([
                    'message' => 'No Data Found'],
                    200);
            }
        } else {
            $validation = Validator::make($request->all(), [
                'mastercategory_id' => 'required',
            ]);

            if ($validation->fails()) {
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;
            }
        }
    }

    /*Get product from main category data*/
    public function productFromMain(Request $request)
    {
        $baseUrl = \Config::get('baseurl');
        $data = array();
        if (isset($request->maincategory_id)) {
            $getmainDetails = MainCategory::select()->where('maincategory.id', $request->maincategory_id)->where('status', 1)->where('isActive', '1')->first();
            if (isset($getmainDetails)) {

                $data['main_category_id'] = $getmainDetails['id'];
                $data['main_category_name'] = $getmainDetails['main_category_name'];

                if (isset($getmainDetails['main_category_banner_image']) != null) {
                    $main_category_banner_image = str_replace('\\', '/', $getmainDetails['main_category_banner_image']);
                    $data['main_category_banner_image'] = $baseUrl['base_url'] . $main_category_banner_image;
                } else {
                    $data['main_category_banner_image'] = null;
                }

                /* Get Product From MainCategory*/
                $getProductfromMainCategory = ProductAllCategory::select('*')
                    ->join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('products_all_category.maincategory_id', $request->maincategory_id)
                    ->where('category_id', 0)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->limit(10)
                    ->get()->toArray();

                $getProductfromMainCategoryCount = ProductAllCategory::select()
                    ->join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('products_all_category.maincategory_id', $request->maincategory_id)
                    ->where('category_id', 0)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->count();

                $data['product_count'] = $getProductfromMainCategoryCount;

                foreach ($getProductfromMainCategory as $getProductfromMainCategoryData) {
                    $productImage = ProductsImage::select()->where('product_id', $getProductfromMainCategoryData['id'])->get()->toArray();
                    $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                    $data['product'][] = array(
                        'product_id' => $getProductfromMainCategoryData['id'],
                        'product_image' => $baseUrl['base_url'] . $product_image,
                        'product_name' => $getProductfromMainCategoryData['product_name'],
                        'product_price' => $getProductfromMainCategoryData['product_price'],
                        'point' => $getProductfromMainCategoryData['point'],
                        'sale' => $getProductfromMainCategoryData['sale'],
                        'sale_price' => isset($getProductfromMainCategoryData['sale_price']) ? $getProductfromMainCategoryData['sale_price'] : null,
                        'packet' => isset($getProductfromMainCategoryData['packet']) ? $getProductfromMainCategoryData['packet'] : null,
                        'quantity' => $getProductfromMainCategoryData['quantity'],
                    );
                }
                /* Get Products From category */
                $getMainCategoryDetails = MainCategoryCategory::select('maincategorycategory.maincategory_id', 'maincategorycategory.category_id', 'category.category_name')
                    ->join('category', 'category.id', '=', 'maincategorycategory.category_id')
                    ->where('maincategorycategory.maincategory_id', $request->maincategory_id)
                    ->get()->toArray();

                foreach ($getMainCategoryDetails as $key => $getMainCategoryValue) {
                    $getProductsDetails = ProductAllCategory::select('products_all_category.*', 'products.*')
                        ->join('products', 'products.id', '=', 'products_all_category.product_id')
                        ->where('products_all_category.category_id', $getMainCategoryValue['category_id'])
                        ->where('products.status', 1)
                        ->where('products.isActive', '1')
                        ->limit(10)
                        ->get()->toArray();

                    $getProductCount = ProductAllCategory::select('products_all_category.*', 'products.*')
                        ->join('products', 'products.id', '=', 'products_all_category.product_id')
                        ->where('products_all_category.category_id', $getMainCategoryValue['category_id'])
                        ->where('products.status', 1)
                        ->where('products.isActive', '1')
                        ->count();

                    if ($getProductCount) {
                        $getMainCategoryValue['product_count'] = $getProductCount;
                    }
                    $categoryArray = array();
                    if (count($getProductsDetails) > 0) {
                        // $data['category'][$key] = $getMainCategoryValue;
                        $categoryArray = $getMainCategoryValue;
                    } else {
                        continue;
                    }

                    $productCategory = array();
                    foreach ($getProductsDetails as $key2 => $getMainCategoryValue2) {
                        $productImage = ProductsImage::select()->where('product_id', $getMainCategoryValue2['id'])->get()->toArray();
                        $product_image = str_replace('\\', '/', $productImage[0]['product_image']);

                        $productCategory[] = array(
                            'product_id' => $getMainCategoryValue2['id'],
                            'product_image' => $baseUrl['base_url'] . $product_image,
                            'product_name' => $getMainCategoryValue2['product_name'],
                            'product_price' => $getMainCategoryValue2['product_price'],
                            'point' => $getMainCategoryValue2['point'],
                            'sale' => $getMainCategoryValue2['sale'],
                            'sale_price' => isset($getMainCategoryValue2['sale_price']) ? $getMainCategoryValue2['sale_price'] : null,
                            'packet' => isset($getMainCategoryValue2['packet']) ? $getMainCategoryValue2['packet'] : null,
                            'quantity' => $getMainCategoryValue2['quantity'],
                        );
                    }
                    $categoryArray['product'] = $productCategory;
                    $data['category'][] = $categoryArray;
                }
                return response([
                    'maincategory' => $data,
                    'message' => 'List of all main category product.'],
                    200);
            } else {
                return response([
                    'message' => 'No Data Found'],
                    200);
            }
        } else {

            $validation = Validator::make($request->all(), [
                'maincategory_id' => 'required',
            ]);

            if ($validation->fails()) {
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;
            }
        }
    }

/*Get product from category data*/
    public function productFromCategory(Request $request)
    {
        $baseUrl = \Config::get('baseurl');
        $data = array();
        if (isset($request->category_id)) {
            $getcategoryDetails = Category::select()->where('category.id', $request->category_id)->where('status', 1)->where('isActive', '1')->first();
            if (isset($getcategoryDetails)) {
                $data['category_id'] = $getcategoryDetails['id'];
                $data['category_name'] = $getcategoryDetails['category_name'];

                if (isset($getcategoryDetails['category_banner_image']) != null) {
                    $category_banner_image = str_replace('\\', '/', $getcategoryDetails['category_banner_image']);
                    $data['category_banner_image'] = $baseUrl['base_url'] . $category_banner_image;} else {
                    $data['category_banner_image'] = null;
                }

                /* Get Products From Category*/
                $getProductfromCategory = ProductAllCategory::select('*')
                    ->join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('products_all_category.category_id', $request->category_id)
                    ->where('subcategory_id', 0)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->limit(10)
                    ->get()->toArray();

                $getProductfromCategoryCount = ProductAllCategory::select('products.*', 'products_all_category.*')
                    ->join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('products_all_category.category_id', $request->category_id)
                    ->where('subcategory_id', 0)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->count();

                $data['product_count'] = $getProductfromCategoryCount;

                foreach ($getProductfromCategory as $getProductfromCategoryValue) {
                    $productImage = ProductsImage::select()->where('product_id', $getProductfromCategoryValue['id'])->get()->toArray();
                    $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                    $data['product'][] = array(
                        'product_id' => $getProductfromCategoryValue['id'],
                        'product_image' => $baseUrl['base_url'] . $product_image,
                        'product_name' => $getProductfromCategoryValue['product_name'],
                        'product_price' => $getProductfromCategoryValue['product_price'],
                        'point' => $getProductfromCategoryValue['point'],
                        'sale' => $getProductfromCategoryValue['sale'],
                        'sale_price' => isset($getProductfromCategoryValue['sale_price']) ? $getProductfromCategoryValue['sale_price'] : null,
                        'packet' => isset($getProductfromCategoryValue['packet']) ? $getProductfromCategoryValue['packet'] : null,
                        'quantity' => $getProductfromCategoryValue['quantity'],
                    );
                }

                /* Get Products From Subcategory */

                $getcategorysDetails = CategorySubCategory::select('categorysubcategory.category_id', 'categorysubcategory.subcategory_id', 'subcategory.sub_category_name')
                    ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
                    ->where('categorysubcategory.category_id', $request->category_id)
                    ->get()->toArray();

                foreach ($getcategorysDetails as $key => $getCategoryValue) {
                    $getProductssDetails = ProductAllCategory::select('products_all_category.*', 'products.*')
                        ->join('products', 'products.id', '=', 'products_all_category.product_id')
                        ->where('products_all_category.subcategory_id', $getCategoryValue['subcategory_id'])
                        ->where('products.status', 1)
                        ->where('products.isActive', '1')
                        ->limit(10)
                        ->get()->toArray();

                    $getProductCount = ProductAllCategory::select('products_all_category.*', 'products.*')
                        ->join('products', 'products.id', '=', 'products_all_category.product_id')
                        ->where('products_all_category.subcategory_id', $getCategoryValue['subcategory_id'])
                        ->where('products.status', 1)
                        ->where('products.isActive', '1')
                        ->count();

                    if ($getProductCount) {
                        $getCategoryValue['product_count'] = $getProductCount;
                    }
                    $subCategoryArray = array();
                    if (count($getProductssDetails) > 0) {
                        $subCategoryArray = $getCategoryValue;
                    } else {
                        continue;
                    }
                    $productSubCategory = array();
                    foreach ($getProductssDetails as $key3 => $getCategoryValue3) {
                        $productImage = ProductsImage::select()->where('product_id', $getCategoryValue3['id'])->get()->toArray();
                        $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                        $productSubCategory[] = array(
                            'product_id' => $getCategoryValue3['id'],
                            'product_image' => $baseUrl['base_url'] . $product_image,
                            'product_name' => $getCategoryValue3['product_name'],
                            'product_price' => $getCategoryValue3['product_price'],
                            'point' => $getCategoryValue3['point'],
                            'sale' => $getCategoryValue3['sale'],
                            'sale_price' => isset($getCategoryValue3['sale_price']) ? $getCategoryValue3['sale_price'] : null,
                            'packet' => isset($getCategoryValue3['packet']) ? $getCategoryValue3['packet'] : null,
                            'quantity' => $getCategoryValue3['quantity'],
                        );
                    }
                    $subCategoryArray['product'] = $productSubCategory;
                    $data['subcategory'][] = $subCategoryArray;
                }

                return response([
                    'category' => $data,
                    'message' => 'List of all category product'],
                    200);
            } else {
                return response([
                    'message' => 'No Data Found'],
                    200);
            }
        } else {

            $validation = Validator::make($request->all(), [
                'category_id' => 'required',
            ]);

            if ($validation->fails()) {
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;
            }
        }
    }

/*Get product from subcategory data*/
    public function productFromSubCategory(Request $request)
    {
        $baseUrl = \Config::get('baseurl');
        $data = array();
        if (isset($request->subcategory_id)) {
            $getsubcategoryDetails = SubCategory::select()->where('subcategory.id', $request->subcategory_id)->where('status', 1)->where('isActive', '1')->first();
            if (isset($getsubcategoryDetails)) {
                $data['subcategory_id'] = $getsubcategoryDetails['id'];
                $data['subcategory_name'] = $getsubcategoryDetails['sub_category_name'];

                $getProductfromCategory = ProductAllCategory::select('products.*')
                    ->join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('products_all_category.subcategory_id', $request->subcategory_id)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->limit(10)
                    ->get()
                    ->toArray();

                $getProductfromSubCategoryCount = ProductAllCategory::join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('products_all_category.subcategory_id', $request->subcategory_id)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->count();

                $data['product_count'] = $getProductfromSubCategoryCount;

                foreach ($getProductfromCategory as $getProductfromSubCategoryData) {
                    $productImage = ProductsImage::select()->where('product_id', $getProductfromSubCategoryData['id'])->get()->toArray();
                    $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                    $data['product'][] = array(
                        'product_id' => $getProductfromSubCategoryData['id'],
                        'product_image' => $baseUrl['base_url'] . $product_image,
                        'product_name' => $getProductfromSubCategoryData['product_name'],
                        'product_price' => $getProductfromSubCategoryData['product_price'],
                        'point' => $getProductfromSubCategoryData['point'],
                        'sale' => $getProductfromSubCategoryData['sale'],
                        'sale_price' => isset($getProductfromSubCategoryData['sale_price']) ? $getProductfromSubCategoryData['sale_price'] : null,
                        'packet' => isset($getProductfromSubCategoryData['packet']) ? $getProductfromSubCategoryData['packet'] : null,
                        'quantity' => $getProductfromSubCategoryData['quantity'],
                    );
                }

                return response([
                    'Subcategory' => $data,
                    'message' => 'List of all Subcategory product'],
                    200);
            } else {
                return response([
                    'message' => 'No Data Found'],
                    200);
            }
        } else {
            $validation = Validator::make($request->all(), [
                'subcategory_id' => 'required',
            ]);

            if ($validation->fails()) {
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;
            }
        }
    }

/* Get Product from Master Category Without Limit */
    public function allProductFromMaster (Request $request)
    {
        $baseUrl = \Config::get('baseurl');
        $data = array();
        if (isset($request->mastercategory_id)) {
            $getmasterDetails = MasterCategory::select()->where('mastercategory.id', $request->mastercategory_id)->where('status', 1)->where('isActive', '1')->first();
            if (isset($getmasterDetails)) {
                $data['mastercategory_id'] = $getmasterDetails['id'];
                $data['mastercategory_name'] = $getmasterDetails['master_category_name'];

                /* Get Product From MaterCategory*/
                $getProductfromMasterCategory = ProductAllCategory::select('*')
                    ->join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('mastercategory_id', $request->mastercategory_id)
                    ->where('maincategory_id', 0)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->get()->toArray();

                $getProductfromMasterCategoryCount = ProductAllCategory::select()
                    ->join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('mastercategory_id', $request->mastercategory_id)
                    ->where('maincategory_id', 0)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->count();

                $data['product_count'] = $getProductfromMasterCategoryCount;

                foreach ($getProductfromMasterCategory as $getProductfromMasterCategoryData) {
                    $productImage = ProductsImage::select()->where('product_id', $getProductfromMasterCategoryData['id'])->get()->toArray();
                    $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                    $data['product'][] = array(
                        'product_id' => $getProductfromMasterCategoryData['id'],
                        'product_image' => $baseUrl['base_url'] . $product_image,
                        'product_name' => $getProductfromMasterCategoryData['product_name'],
                        'product_price' => $getProductfromMasterCategoryData['product_price'],
                        'point' => $getProductfromMasterCategoryData['point'],
                        'sale' => $getProductfromMasterCategoryData['sale'],
                        'sale_price' => isset($getProductfromMasterCategoryData['sale_price']) ? $getProductfromMasterCategoryData['sale_price'] : null,
                        'packet' => isset($getProductfromMasterCategoryData['packet']) ? $getProductfromMasterCategoryData['packet'] : null,
                        'quantity' => $getProductfromMasterCategoryData['quantity'],
                    );
                }

                /* Get Products From Maincategory */
                $getMasterMainDetails = MasterMainCategory::select('mastermaincategory.mastercategory_id', 'mastermaincategory.maincategory_id', 'maincategory.main_category_name')
                    ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
                    ->where('mastermaincategory.mastercategory_id', $request->mastercategory_id)
                    ->get()->toArray();

                foreach ($getMasterMainDetails as $key => $getMasterCategoryValue) {
                    $getProductDetails = ProductAllCategory::select('products_all_category.*', 'products.*')
                        ->join('products', 'products.id', '=', 'products_all_category.product_id')
                        ->where('products_all_category.maincategory_id', $getMasterCategoryValue['maincategory_id'])
                        ->where('products.status', 1)
                        ->where('products.isActive', '1')
                        ->get()->toArray();

                    $getProductCount = ProductAllCategory::select('products_all_category.*', 'products.*')
                        ->join('products', 'products.id', '=', 'products_all_category.product_id')
                        ->where('products_all_category.maincategory_id', $getMasterCategoryValue['maincategory_id'])
                        ->where('products.status', 1)
                        ->where('products.isActive', '1')
                        ->count();

                    if ($getProductCount) {
                        $getMasterCategoryValue['product_count'] = $getProductCount;
                    }

                    $mainCategoryArray = array();
                    if (count($getProductDetails) > 0) {
                        $mainCategoryArray = $getMasterCategoryValue;
                        //$data['main_category'][$key] = $getMasterCategoryValue;
                    } else {
                        continue;
                    }

                    $productMain = array();
                    foreach ($getProductDetails as $key1 => $getMasterCategoryValue1) {
                        $productImage = ProductsImage::select()->where('product_id', $getMasterCategoryValue1['id'])->get()->toArray();
                        $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                        $productMain[] = array(

                            'product_id' => $getMasterCategoryValue1['id'],
                            'product_image' => $baseUrl['base_url'] . $product_image,
                            'product_name' => $getMasterCategoryValue1['product_name'],
                            'product_price' => $getMasterCategoryValue1['product_price'],
                            'point' => $getMasterCategoryValue1['point'],
                            'sale' => $getMasterCategoryValue1['sale'],
                            'sale_price' => isset($getMasterCategoryValue1['sale_price']) ? $getMasterCategoryValue1['sale_price'] : null,
                            'packet' => isset($getMasterCategoryValue1['packet']) ? $getMasterCategoryValue1['packet'] : null,
                            'quantity' => $getMasterCategoryValue1['quantity'],
                        );
                    }
                    $mainCategoryArray['product'] = $productMain;
                    $data['main_category'][] = $mainCategoryArray;
                }
                return response([
                    'mastercategory' => $data,
                    'message' => 'List of all master category product.'],
                    200);
            } else {
                return response([
                    'message' => 'No Data Found'],
                    200);
            }
        } else {
            $validation = Validator::make($request->all(), [
                'mastercategory_id' => 'required',
            ]);

            if ($validation->fails()) {
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;
            }
        }
    }
/*Get Product from Main Category Without Limit*/
    public function allProductFromMain(Request $request)
    {

        $baseUrl = \Config::get('baseurl');
        $data = array();
        if (isset($request->maincategory_id)) {
            $getmainDetails = MainCategory::select()->where('maincategory.id', $request->maincategory_id)->where('status', 1)->where('isActive', '1')->first();
            if (isset($getmainDetails)) {
                $data['main_category_id'] = $getmainDetails['id'];
                $data['main_category_name'] = $getmainDetails['main_category_name'];

                if (isset($getmainDetails['main_category_banner_image']) != null) {
                    $main_category_banner_image = str_replace('\\', '/', $getmainDetails['main_category_banner_image']);
                    $data['main_category_banner_image'] = $baseUrl['base_url'] . $main_category_banner_image;
                } else {
                    $data['main_category_banner_image'] = null;
                }

                /* Get Product From MainCategory*/
                $getProductfromMainCategory = ProductAllCategory::select('*')
                    ->join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('products_all_category.maincategory_id', $request->maincategory_id)
                    ->where('category_id', 0)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->get()->toArray();

                $getProductfromMainCategoryCount = ProductAllCategory::select()
                    ->join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('products_all_category.maincategory_id', $request->maincategory_id)
                    ->where('category_id', 0)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->count();

                $data['product_count'] = $getProductfromMainCategoryCount;

                foreach ($getProductfromMainCategory as $getProductfromMainCategoryData) {
                    $productImage = ProductsImage::select()->where('product_id', $getProductfromMainCategoryData['id'])->get()->toArray();
                    $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                    $data['product'][] = array(
                        'product_id' => $getProductfromMainCategoryData['id'],
                        'product_image' => $baseUrl['base_url'] . $product_image,
                        'product_name' => $getProductfromMainCategoryData['product_name'],
                        'product_price' => $getProductfromMainCategoryData['product_price'],
                        'point' => $getProductfromMainCategoryData['point'],
                        'sale' => $getProductfromMainCategoryData['sale'],
                        'sale_price' => isset($getProductfromMainCategoryData['sale_price']) ? $getProductfromMainCategoryData['sale_price'] : null,
                        'packet' => isset($getProductfromMainCategoryData['packet']) ? $getProductfromMainCategoryData['packet'] : null,
                        'quantity' => $getProductfromMainCategoryData['quantity'],
                    );
                }
                /* Get Products From category */
                $getMainCategoryDetails = MainCategoryCategory::select('maincategorycategory.maincategory_id', 'maincategorycategory.category_id', 'category.category_name')
                    ->join('category', 'category.id', '=', 'maincategorycategory.category_id')
                    ->where('maincategorycategory.maincategory_id', $request->maincategory_id)
                    ->get()->toArray();

                $getcategorysProductDetails = ProductAllCategory::select('maincategory.*', 'products.*')
                    ->join('maincategory', 'maincategory.id', '=', 'products_all_category.maincategory_id')
                    ->join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('products_all_category.maincategory_id', $request->maincategory_id)
                    ->get()->toArray();

                foreach ($getMainCategoryDetails as $key => $getMainCategoryValue) {
                    $getProductsDetails = ProductAllCategory::select('products_all_category.*', 'products.*')
                        ->join('products', 'products.id', '=', 'products_all_category.product_id')
                        ->where('products_all_category.category_id', $getMainCategoryValue['category_id'])
                        ->where('products.status', 1)
                        ->where('products.isActive', '1')
                        ->limit(10)
                        ->get()->toArray();

                    $getProductCount = ProductAllCategory::select('products_all_category.*', 'products.*')
                        ->join('products', 'products.id', '=', 'products_all_category.product_id')
                        ->where('products_all_category.category_id', $getMainCategoryValue['category_id'])
                        ->where('products.status', 1)
                        ->where('products.isActive', '1')
                        ->count();

                    if ($getProductCount) {
                        $getMainCategoryValue['product_count'] = $getProductCount;
                    }
                    $categoryArray = array();
                    if (count($getProductsDetails) > 0) {
                        //$data['category'][$key] = $getMainCategoryValue;
                        $categoryArray = $getMainCategoryValue;
                    } else {
                        continue;
                    }
                    $productCategory = array();
                    foreach ($getProductsDetails as $key2 => $getMainCategoryValue2) {
                        $productImage = ProductsImage::select()->where('product_id', $getMainCategoryValue2['id'])->get()->toArray();
                        $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                        $productCategory[] = array(
                            'product_id' => $getMainCategoryValue2['id'],
                            'product_image' => $baseUrl['base_url'] . $product_image,
                            'product_name' => $getMainCategoryValue2['product_name'],
                            'product_price' => $getMainCategoryValue2['product_price'],
                            'point' => $getMainCategoryValue2['point'],
                            'sale' => $getMainCategoryValue2['sale'],
                            'sale_price' => isset($getMainCategoryValue2['sale_price']) ? $getMainCategoryValue2['sale_price'] : null,
                            'packet' => isset($getMainCategoryValue2['packet']) ? $getMainCategoryValue2['packet'] : null,
                            'quantity' => $getMainCategoryValue2['quantity'],
                        );
                    }
                    $categoryArray['product'] = $productCategory;
                    $data['category'][] = $categoryArray;
                }
                return response([
                    'maincategory' => $data,
                    'message' => 'List of all main category product.'],
                    200);
            } else {
                return response([
                    'message' => 'No Data Found'],
                    200);
            }
        } else {

            $validation = Validator::make($request->all(), [
                'maincategory_id' => 'required',
            ]);

            if ($validation->fails()) {
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;
            }
        }
    }

/*Get Product from Category Without Limit*/
    public function allProductFromCategory (Request $request)
    {
        $baseUrl = \Config::get('baseurl');
        $data = array();
        if (isset($request->category_id)) {
            $getcategoryDetails = Category::select()->where('category.id', $request->category_id)->where('status', 1)->where('isActive', '1')->first();
            if (isset($getcategoryDetails)) {
                $data['category_id'] = $getcategoryDetails['id'];
                $data['category_name'] = $getcategoryDetails['category_name'];

                if (isset($getcategoryDetails['category_banner_image']) != null) {
                    $category_banner_image = str_replace('\\', '/', $getcategoryDetails['category_banner_image']);
                    $data['category_banner_image'] = $baseUrl['base_url'] . $category_banner_image;} else {
                    $data['category_banner_image'] = null;
                }

                /* Get Products From Category*/
                $getProductfromCategory = ProductAllCategory::select('*')
                    ->join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('products_all_category.category_id', $request->category_id)
                    ->where('subcategory_id', 0)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->get()->toArray();

                $getProductfromCategoryCount = ProductAllCategory::select('products.*', 'products_all_category.*')
                    ->join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('products_all_category.category_id', $request->category_id)
                    ->where('subcategory_id', 0)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->count();

                $data['product_count'] = $getProductfromCategoryCount;

                foreach ($getProductfromCategory as $getProductfromCategoryValue) {
                    $productImage = ProductsImage::select()->where('product_id', $getProductfromCategoryValue['id'])->get()->toArray();
                    $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                    $data['product'][] = array(
                        'product_id' => $getProductfromCategoryValue['id'],
                        'product_image' => $baseUrl['base_url'] . $product_image,
                        'product_name' => $getProductfromCategoryValue['product_name'],
                        'product_price' => $getProductfromCategoryValue['product_price'],
                        'point' => $getProductfromCategoryValue['point'],
                        'sale' => $getProductfromCategoryValue['sale'],
                        'sale_price' => isset($getProductfromCategoryValue['sale_price']) ? $getProductfromCategoryValue['sale_price'] : null,
                        'packet' => isset($getProductfromCategoryValue['packet']) ? $getProductfromCategoryValue['packet'] : null,
                        'quantity' => $getProductfromCategoryValue['quantity'],
                    );

                }

                /* Get Products From Subcategory */

                $getcategorysDetails = CategorySubCategory::select('categorysubcategory.category_id', 'categorysubcategory.subcategory_id', 'subcategory.sub_category_name')
                    ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
                    ->where('categorysubcategory.category_id', $request->category_id)
                    ->get()->toArray();

                foreach ($getcategorysDetails as $key => $getCategoryValue) {
                    $getProductssDetails = ProductAllCategory::select('products_all_category.*', 'products.*')
                        ->join('products', 'products.id', '=', 'products_all_category.product_id')
                        ->where('products_all_category.subcategory_id', $getCategoryValue['subcategory_id'])
                        ->where('products.status', 1)
                        ->where('products.isActive', '1')
                        ->get()->toArray();

                    $getProductCount = ProductAllCategory::select('products_all_category.*', 'products.*')
                        ->join('products', 'products.id', '=', 'products_all_category.product_id')
                        ->where('products_all_category.subcategory_id', $getCategoryValue['subcategory_id'])
                        ->where('products.status', 1)
                        ->where('products.isActive', '1')
                        ->count();

                    if ($getProductCount) {
                        $getCategoryValue['product_count'] = $getProductCount;
                    }
                    $subCategoryArray = array();
                    if (count($getProductssDetails) > 0) {
                        // $data['subcategory'][$key] = $getCategoryValue;
                        $subCategoryArray = $getCategoryValue;
                    } else {
                        continue;
                    }
                    $productSubCategory = array();
                    foreach ($getProductssDetails as $key3 => $getCategoryValue3) {
                        $productImage = ProductsImage::select()->where('product_id', $getCategoryValue3['id'])->get()->toArray();
                        $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                        $productSubCategory[] = array(
                            'product_id' => $getCategoryValue3['id'],
                            'product_image' => $baseUrl['base_url'] . $product_image,
                            'product_name' => $getCategoryValue3['product_name'],
                            'product_price' => $getCategoryValue3['product_price'],
                            'point' => $getCategoryValue3['point'],
                            'sale' => $getCategoryValue3['sale'],
                            'sale_price' => isset($getCategoryValue3['sale_price']) ? $getCategoryValue3['sale_price'] : null,
                            'packet' => isset($getCategoryValue3['packet']) ? $getCategoryValue3['packet'] : null,
                            'quantity' => $getCategoryValue3['quantity'],
                        );
                    }
                    $subCategoryArray['product'] = $productSubCategory;
                    $data['subcategory'][] = $subCategoryArray;
                }

                return response([
                    'category' => $data,
                    'message' => 'List of all category product'],
                    200);
            } else {
                return response([
                    'message' => 'No Data Found'],
                    200);
            }
        } else {

            $validation = Validator::make($request->all(), [
                'category_id' => 'required',
            ]);

            if ($validation->fails()) {
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;
            }
        }
    }

/*Get Product from Subcategory Without Limit*/
    public function allProductFromSubCategory(Request $request)
    {
        $baseUrl = \Config::get('baseurl');
        $data = array();
        if (isset($request->subcategory_id)) {
            $getsubcategoryDetails = SubCategory::select()->where('subcategory.id', $request->subcategory_id)->where('status', 1)->where('isActive', '1')->first();
            if (isset($getsubcategoryDetails)) {
                $data['subcategory_id'] = $getsubcategoryDetails['id'];
                $data['subcategory_name'] = $getsubcategoryDetails['sub_category_name'];

                $getProductfromCategory = ProductAllCategory::select('products.*')
                    ->join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('products_all_category.subcategory_id', $request->subcategory_id)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->get()
                    ->toArray();

                $getProductfromSubCategoryCount = ProductAllCategory::join('products', 'products.id', '=', 'products_all_category.product_id')
                    ->where('products_all_category.subcategory_id', $request->subcategory_id)
                    ->where('products.status', 1)
                    ->where('products.isActive', '1')
                    ->count();

                $data['product_count'] = $getProductfromSubCategoryCount;

                foreach ($getProductfromCategory as $getProductfromSubCategoryData) {
                    $productImage = ProductsImage::select()->where('product_id', $getProductfromSubCategoryData['id'])->get()->toArray();
                    $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                    $data['product'][] = array(
                        'product_id' => $getProductfromSubCategoryData['id'],
                        'product_image' => $baseUrl['base_url'] . $product_image,
                        'product_name' => $getProductfromSubCategoryData['product_name'],
                        'product_price' => $getProductfromSubCategoryData['product_price'],
                        'point' => $getProductfromSubCategoryData['point'],
                        'sale' => $getProductfromSubCategoryData['sale'],
                        'sale_price' => isset($getProductfromSubCategoryData['sale_price']) ? $getProductfromSubCategoryData['sale_price'] : null,
                        'packet' => isset($getProductfromSubCategoryData['packet']) ? $getProductfromSubCategoryData['packet'] : null,
                        'quantity' => $getProductfromSubCategoryData['quantity'],
                    );
                }

                return response([
                    'Subcategory' => $data,
                    'message' => 'List of all Subcategory product'],
                    200);
            } else {
                return response([
                    'message' => 'No Data Found'],
                    200);
            }
        } else {
            $validation = Validator::make($request->all(), [
                'subcategory_id' => 'required',
            ]);

            if ($validation->fails()) {
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;
            }
        }
    }

}
