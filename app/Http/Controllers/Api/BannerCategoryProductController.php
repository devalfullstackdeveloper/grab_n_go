<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\CategorySubCategory;
use App\Models\MainCategory;
use App\Models\MainCategoryCategory;
use App\Models\MasterMainCategory;
use App\Models\Product;
use App\Models\ProductAllCategory;
use App\Models\ProductsImage;
use App\Models\SubCategory;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Config;

class BannerCategoryProductController extends Controller
{
    public function bannerCategoryProduct(Request $request)
    {
        $baseUrl = \Config::get('baseurl');

        $bannerProductarray = array();
        if (isset($request->banner_id)) {
            $getBannerCategoryData = Banner::select('banners.*', 'mastercategory.isActive', 'maincategory.isActive', 'category.isActive', 'subcategory.isActive', 'mastercategory.master_category_name', 'mastercategory.master_category_image', 'maincategory.main_category_name', 'maincategory.main_category_image', 'category.category_name', 'subcategory.sub_category_name', 'category.category_image', 'subcategory.sub_category_image')
                ->leftJoin('mastercategory', 'mastercategory.id', '=', 'banners.mastercategory_id')
                ->leftJoin('maincategory', 'maincategory.id', '=', 'banners.maincategory_id')
                ->leftJoin('category', 'category.id', '=', 'banners.category_id')
                ->leftJoin('subcategory', 'subcategory.id', '=', 'banners.subcategory_id')
                ->where('banners.isActive', "1")
                ->where('banners.id', $request->banner_id)
                ->where('banners.status', 1)
                ->first();

            if ($getBannerCategoryData != null) {
                $getBannerCategoryData = $getBannerCategoryData->toArray();
            } else {
                return response([
                    'message' => 'Successful',
                    'bannerproductdata' => 'No Banner Found',
                ]);
            }

            $bannerProductarray['banner_name'] = $getBannerCategoryData['banner_name'];
            $bannerProductarray['banner_image'] = $baseUrl['base_url'] . $getBannerCategoryData['banner_image'];

            if ($getBannerCategoryData['mastercategory_id'] != 0 && $getBannerCategoryData['maincategory_id'] == 0 && $getBannerCategoryData['category_id'] == 0 && $getBannerCategoryData['subcategory_id'] == 0) {
                $masterMainCategoryData = MasterMainCategory::where('mastercategory_id', '=', $getBannerCategoryData['mastercategory_id'])->get()->toArray();
                foreach ($masterMainCategoryData as $masterMainCategoryDataValue) {
                    $mainCategory = MainCategory::where('id', '=', $masterMainCategoryDataValue['maincategory_id'])->where('isActive', '1')->get()->toArray();

                    if ($mainCategory != 0) {
                        $mainCategoryData = ProductAllCategory::where('maincategory_id', '=', $masterMainCategoryDataValue['maincategory_id'])->get()->toArray();
                        $data = array();
                        foreach ($mainCategoryData as $mainCategoryDataValue) {
                            $getProductmain = Product::where('id', '=', $mainCategoryDataValue['product_id'])->where('isActive', '1')->limit(10)->get()->toArray();
                            foreach ($getProductmain as $getMainCategoryValue) {
                                $productImage = ProductsImage::select()->where('product_id', $getMainCategoryValue['id'])->get()->toArray();
                                if ($mainCategoryDataValue['product_id'] == $getMainCategoryValue['id']) {
                                    $data[] = array(
                                        'product_id' => $getMainCategoryValue['id'],
                                        'product_image' => $baseUrl['base_url'] . $productImage[0]['product_image'],
                                        'product_name' => $getMainCategoryValue['product_name'],
                                        'product_price' => $getMainCategoryValue['product_price'],
                                        'point' => $getMainCategoryValue['point'],
                                        'sale' => $getMainCategoryValue['sale'],
                                        'sale_price' => isset($getMainCategoryValue['sale_price']) ? $getMainCategoryValue['sale_price'] : null,
                                        'packet' => isset($getMainCategoryValue['packet']) ? $getMainCategoryValue['packet'] : null,
                                        'quantity' => $getMainCategoryValue['quantity'],
                                    );
                                }
                            }
                        }
                        $categoryDataArray = array();
                        if (!empty($data)) {
                            $categoryDataArray['mastercategory_id'] = $getBannerCategoryData['mastercategory_id'];
                            foreach ($mainCategory as $mainCategoryValue) {
                                $categoryDataArray['maincategory_id'] = $mainCategoryValue['id'];
                                $categoryDataArray['category_name'] = $mainCategoryValue['main_category_name'];
                            }
                            $categoryDataArray['product'] = $data;
                            $bannerProductarray['category'][] = $categoryDataArray;
                        }
                    }
                }
            }
            if ($getBannerCategoryData['mastercategory_id'] != 0 && $getBannerCategoryData['maincategory_id'] != 0 && $getBannerCategoryData['category_id'] == 0 && $getBannerCategoryData['subcategory_id'] == 0) {
                $flag = 0;
                $mainCategoryCategoryData = MainCategoryCategory::where('maincategory_id', '=', $getBannerCategoryData['maincategory_id'])->get()->toArray();
                foreach ($mainCategoryCategoryData as $mainCategoryCategoryDataValue) {
                    $CategoryData = Category::where('id', '=', $mainCategoryCategoryDataValue['category_id'])->get()->toArray();
                    if (!empty($CategoryData)) {
                        $productCategoryData = ProductAllCategory::where('category_id', '=', $mainCategoryCategoryDataValue['category_id'])->get()->toArray();
                        $data = array();
                        foreach ($productCategoryData as $productCategoryDataValue) {
                            $getProductCategory = Product::where('id', '=', $productCategoryDataValue['product_id'])->where('isActive', '1')->limit(10)->get()->toArray();
                            foreach ($getProductCategory as $getCategoryValue) {
                                $productImage = ProductsImage::select()->where('product_id', $getCategoryValue['id'])->get()->toArray();
                                if ($productCategoryDataValue['product_id'] == $getCategoryValue['id']) {
                                    $data[] = array(
                                        'product_id' => $getCategoryValue['id'],
                                        'product_image' => $baseUrl['base_url'] . $productImage[0]['product_image'],
                                        'product_name' => $getCategoryValue['product_name'],
                                        'product_price' => $getCategoryValue['product_price'],
                                        'point' => $getCategoryValue['point'],
                                        'sale' => $getCategoryValue['sale'],
                                        'sale_price' => isset($getCategoryValue['sale_price']) ? $getCategoryValue['sale_price'] : null,
                                        'packet' => isset($getCategoryValue['packet']) ? $getCategoryValue['packet'] : null,
                                        'quantity' => $getCategoryValue['quantity'],
                                    );
                                }
                            }
                            $flag++;
                        }
                        $categoryDataArray = array();
                        if (!empty($data)) {
                            $categoryDataArray['maincategory_id'] = $getBannerCategoryData['maincategory_id'];
                            foreach ($CategoryData as $CategoryDataValue) {
                                $categoryDataArray['maincategory_id'] = $CategoryDataValue['id'];
                                $categoryDataArray['category_name'] = $CategoryDataValue['category_name'];
                            }
                            $categoryDataArray['product'] = $data;
                            $bannerProductarray['category'][] = $categoryDataArray;
                        }
                    }
                }
                if ($flag == 0) {
                    $mainCategoryCategoryData = MainCategory::where('id', '=', $getBannerCategoryData['maincategory_id'])->where('isActive', '1')->get()->toArray();
                    foreach ($mainCategoryCategoryData as $mainCategoryCategoryDataValue) {
                        $productMainCategoryData = ProductAllCategory::where('maincategory_id', '=', $getBannerCategoryData['maincategory_id'])->get()->toArray();
                        $data = array();
                        foreach ($productMainCategoryData as $productMainCategoryDataValue) {
                            $getProductCategory = Product::where('id', '=', $productMainCategoryDataValue['product_id'])->where('isActive', '1')->limit(10)->get()->toArray();
                            foreach ($getProductCategory as $getCategoryValue) {
                                $productImage = ProductsImage::select()->where('product_id', $getCategoryValue['id'])->get()->toArray();

                                if ($productMainCategoryDataValue['product_id'] == $getCategoryValue['id']) {
                                    $data[] = array(
                                        'product_id' => $getCategoryValue['id'],
                                        'product_image' => $baseUrl['base_url'] . $productImage[0]['product_image'],
                                        'product_name' => $getCategoryValue['product_name'],
                                        'product_price' => $getCategoryValue['product_price'],
                                        'point' => $getCategoryValue['point'],
                                        'sale' => $getCategoryValue['sale'],
                                        'sale_price' => isset($getCategoryValue['sale_price']) ? $getCategoryValue['sale_price'] : null,
                                        'packet' => isset($getCategoryValue['packet']) ? $getCategoryValue['packet'] : null,
                                        'quantity' => $getCategoryValue['quantity'],
                                    );
                                }
                            }
                        }
                        $categoryDataArray = array();
                        if (!empty($data)) {
                            $categoryDataArray['maincategory_id'] = $getBannerCategoryData['maincategory_id'];
                            foreach ($mainCategoryCategoryData as $mainCategoryCategoryDataValue) {
                                $categoryDataArray['maincategory_id'] = $mainCategoryCategoryDataValue['id'];
                                $categoryDataArray['category_name'] = $mainCategoryCategoryDataValue['main_category_name'];
                            }
                            $categoryDataArray['product'] = $data;
                            $bannerProductarray['category'][] = $categoryDataArray;
                        }
                    }
                }
            }

            if ($getBannerCategoryData['mastercategory_id'] != 0 && $getBannerCategoryData['maincategory_id'] != 0 && $getBannerCategoryData['category_id'] != 0 && $getBannerCategoryData['subcategory_id'] == 0) {
                $flag = 0;
                $categorySubCategoryData = CategorySubCategory::where('category_id', '=', $getBannerCategoryData['category_id'])->get()->toArray();
                foreach ($categorySubCategoryData as $categorySubCategoryDataValue) {
                    $subCategory = SubCategory::where('id', '=', $categorySubCategoryDataValue['subcategory_id'])->where('isActive', '1')->get()->toArray();
                    if ($subCategory != 0) {
                        $subCategoryData = ProductAllCategory::where('subcategory_id', '=', $categorySubCategoryDataValue['subcategory_id'])->get()->toArray();
                        $data = array();
                        foreach ($subCategoryData as $subCategoryDataValue) {
                            $getSubProduct = Product::where('id', '=', $subCategoryDataValue['product_id'])->where('isActive', '1')->limit(10)->get()->toArray();
                            foreach ($getSubProduct as $getSubCategoryValue) {
                                $productImage = ProductsImage::select()->where('product_id', $getSubCategoryValue['id'])->get()->toArray();

                                if ($subCategoryDataValue['product_id'] == $getSubCategoryValue['id']) {
                                    $data[] = array(
                                        'product_id' => $getSubCategoryValue['id'],
                                        'product_image' => $baseUrl['base_url'] . $productImage[0]['product_image'],
                                        'product_name' => $getSubCategoryValue['product_name'],
                                        'product_price' => $getSubCategoryValue['product_price'],
                                        'point' => $getSubCategoryValue['point'],
                                        'sale' => $getSubCategoryValue['sale'],
                                        'sale_price' => isset($getSubCategoryValue['sale_price']) ? $getSubCategoryValue['sale_price'] : null,
                                        'packet' => isset($getSubCategoryValue['packet']) ? $getSubCategoryValue['packet'] : null,
                                        'quantity' => $getSubCategoryValue['quantity'],
                                    );
                                }
                            }
                            $flag++;
                        }

                        $categoryDataArray = array();
                        if (!empty($data)) {
                            $categoryDataArray['category_id'] = $getBannerCategoryData['category_id'];
                            foreach ($subCategory as $subCategoryValue) {
                                $categoryDataArray['subcategory_id'] = $subCategoryValue['id'];
                                $categoryDataArray['category_name'] = $subCategoryValue['sub_category_name'];
                            }
                            $categoryDataArray['product'] = $data;
                            $bannerProductarray['category'][] = $categoryDataArray;
                        }
                    }
                }
                if ($flag == 0) {
                    $categoryData = Category::where('id', '=', $getBannerCategoryData['category_id'])->where('isActive', '1')->get()->toArray();
                    foreach ($categoryData as $mainCategoryCategoryDataValue) {
                        $productCategoryData = ProductAllCategory::where('category_id', '=', $getBannerCategoryData['category_id'])->get()->toArray();
                        $data = array();
                        foreach ($productCategoryData as $productCategoryDataValue) {
                            $getProductCategory = Product::where('id', '=', $productCategoryDataValue['product_id'])->where('isActive', '1')->limit(10)->get()->toArray();
                            foreach ($getProductCategory as $getCategoryValue) {
                                $productImage = ProductsImage::select()->where('product_id', $getCategoryValue['id'])->get()->toArray();

                                if ($productCategoryDataValue['product_id'] == $getCategoryValue['id']) {
                                    $data[] = array(
                                        'product_id' => $getCategoryValue['id'],
                                        'product_image' => $baseUrl['base_url'] . $productImage[0]['product_image'],
                                        'product_name' => $getCategoryValue['product_name'],
                                        'product_price' => $getCategoryValue['product_price'],
                                        'point' => $getCategoryValue['point'],
                                        'sale' => $getCategoryValue['sale'],
                                        'sale_price' => isset($getCategoryValue['sale_price']) ? $getCategoryValue['sale_price'] : null,
                                        'packet' => isset($getCategoryValue['packet']) ? $getCategoryValue['packet'] : null,
                                        'quantity' => $getCategoryValue['quantity'],
                                    );
                                }
                            }
                        }
                        $categoryDataArray = array();
                        if (!empty($data)) {
                            $categoryDataArray['category_id'] = $getBannerCategoryData['category_id'];
                            foreach ($categoryData as $categoryDataValue) {
                                $categoryDataArray['category_id'] = $categoryDataValue['id'];
                                $categoryDataArray['category_name'] = $categoryDataValue['category_name'];
                            }
                            $categoryDataArray['product'] = $data;
                            $bannerProductarray['category'][] = $categoryDataArray;
                        }
                    }
                }
            }
            if ($getBannerCategoryData['mastercategory_id'] != 0 && $getBannerCategoryData['maincategory_id'] != 0 && $getBannerCategoryData['category_id'] != 0 && $getBannerCategoryData['subcategory_id'] != 0) {
                $subCategoryAllData = SubCategory::where('id', '=', $getBannerCategoryData['subcategory_id'])->where('isActive', '1')->get()->toArray();
                foreach ($subCategoryAllData as $key => $subCategoryAllDataValue) {
                    $productSubCategoryData = ProductAllCategory::where('subcategory_id', '=', $getBannerCategoryData['subcategory_id'])->get()->toArray();
                    $data = array();
                    foreach ($productSubCategoryData as $productSubCategoryDataValue) {
                        $getProductCategory = Product::where('id', '=', $productSubCategoryDataValue['product_id'])->where('isActive', '1')->limit(10)->get()->toArray();
                        foreach ($getProductCategory as $getAllSubCategoryValue) {
                            $productImage = ProductsImage::select()->where('product_id', $getAllSubCategoryValue['id'])->get()->toArray();
                            if ($productSubCategoryDataValue['product_id'] == $getAllSubCategoryValue['id']) {
                                $data[] = array(
                                    'product_id' => $getAllSubCategoryValue['id'],
                                    'product_image' => $baseUrl['base_url'] . $product_image,
                                    'product_name' => $getAllSubCategoryValue['product_name'],
                                    'product_price' => $getAllSubCategoryValue['product_price'],
                                    'point' => $getAllSubCategoryValue['point'],
                                    'sale' => $getAllSubCategoryValue['sale'],
                                    'sale_price' => isset($getAllSubCategoryValue['sale_price']) ? $getAllSubCategoryValue['sale_price'] : null,
                                    'packet' => isset($getAllSubCategoryValue['packet']) ? $getAllSubCategoryValue['packet'] : null,
                                    'quantity' => $getAllSubCategoryValue['quantity'],
                                );
                            }
                        }
                    }
                    $categoryDataArray = array();
                    if (!empty($data)) {
                        $categoryDataArray['category_id'] = $getBannerCategoryData['category_id'];
                        foreach ($subCategoryAllData as $subCategoryAllDataValue) {
                            $categoryDataArray['subcategory_id'] = $subCategoryAllDataValue['id'];
                            $categoryDataArray['category_name'] = $subCategoryAllDataValue['sub_category_name'];
                        }
                        $categoryDataArray['product'] = $data;
                        $bannerProductarray['category'][] = $categoryDataArray;
                    }
                }
            }

            $returnArray = array(
                'message' => 'Successfull',
                'messagecode' => 1,
            );

            if (isset($bannerProductarray['category'])) {
                $returnArray['bannerproductdata'] = $bannerProductarray;
            } else {
                $returnArray = array(
                    'message' => 'Successfull',
                    'messagecode' => 0,
                    'bannerproductdata' => 'No data found in Banner Product',
                );
            }
            return response($returnArray, 200);
        } else {
            $validation = Validator::make($request->all(), [
                'banner_id' => 'required',
            ]);

            if ($validation->fails()) {
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;
            }
        }
    }

    public function allBannerCategoryProduct(Request $request)
    {
        $baseUrl = \Config::get('baseurl');

        $bannerProductarray = array();
        if (isset($request->banner_id)) {
            $getBannerCategoryData = Banner::select('banners.*', 'mastercategory.isActive', 'maincategory.isActive', 'category.isActive', 'subcategory.isActive', 'mastercategory.master_category_name', 'mastercategory.master_category_image', 'maincategory.main_category_name', 'maincategory.main_category_image', 'category.category_name', 'subcategory.sub_category_name', 'category.category_image', 'subcategory.sub_category_image')
                ->leftJoin('mastercategory', 'mastercategory.id', '=', 'banners.mastercategory_id')
                ->leftJoin('maincategory', 'maincategory.id', '=', 'banners.maincategory_id')
                ->leftJoin('category', 'category.id', '=', 'banners.category_id')
                ->leftJoin('subcategory', 'subcategory.id', '=', 'banners.subcategory_id')
                ->where('banners.isActive', "1")
                ->where('banners.id', $request->banner_id)
                ->where('banners.status', 1)
                ->first();

            if ($getBannerCategoryData != null) {
                $getBannerCategoryData = $getBannerCategoryData->toArray();
            } else {
                return response([
                    'message' => 'Successful',
                    'bannerproductdata' => 'No Banner Found',
                ]);
            }
            $banner_image = str_replace('\\', '/', $getBannerCategoryData['banner_image']);
            $bannerProductarray['banner_name'] = $getBannerCategoryData['banner_name'];
            $bannerProductarray['banner_image'] = $baseUrl['base_url'] . $banner_image;

            if ($getBannerCategoryData['mastercategory_id'] != 0 && $getBannerCategoryData['maincategory_id'] == 0 && $getBannerCategoryData['category_id'] == 0 && $getBannerCategoryData['subcategory_id'] == 0) {
                $masterMainCategoryData = MasterMainCategory::where('mastercategory_id', '=', $getBannerCategoryData['mastercategory_id'])->get()->toArray();
                foreach ($masterMainCategoryData as $masterMainCategoryDataValue) {
                    $mainCategory = MainCategory::where('id', '=', $masterMainCategoryDataValue['maincategory_id'])->where('isActive', '1')->get()->toArray();

                    if ($mainCategory != 0) {
                        $mainCategoryData = ProductAllCategory::where('maincategory_id', '=', $masterMainCategoryDataValue['maincategory_id'])->get()->toArray();
                        $data = array();
                        foreach ($mainCategoryData as $mainCategoryDataValue) {
                            $getProductmain = Product::where('id', '=', $mainCategoryDataValue['product_id'])->where('isActive', '1')->get()->toArray();
                            foreach ($getProductmain as $getMainCategoryValue) {
                                $productImage = ProductsImage::select()->where('product_id', $getMainCategoryValue['id'])->get()->toArray();
                                $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                                if ($mainCategoryDataValue['product_id'] == $getMainCategoryValue['id']) {
                                    $data[] = array(
                                        'product_id' => $getMainCategoryValue['id'],
                                        'product_image' => $baseUrl['base_url'] . $product_image,
                                        'product_name' => $getMainCategoryValue['product_name'],
                                        'product_price' => $getMainCategoryValue['product_price'],
                                        'point' => $getMainCategoryValue['point'],
                                        'sale' => $getMainCategoryValue['sale'],
                                        'sale_price' => isset($getMainCategoryValue['sale_price']) ? $getMainCategoryValue['sale_price'] : null,
                                        'packet' => isset($getMainCategoryValue['packet']) ? $getMainCategoryValue['packet'] : null,
                                        'quantity' => $getMainCategoryValue['quantity'],
                                    );
                                }
                            }
                        }
                        $categoryDataArray = array();
                        if (!empty($data)) {
                            $categoryDataArray['mastercategory_id'] = $getBannerCategoryData['mastercategory_id'];
                            foreach ($mainCategory as $mainCategoryValue) {
                                $categoryDataArray['maincategory_id'] = $mainCategoryValue['id'];
                                $categoryDataArray['category_name'] = $mainCategoryValue['main_category_name'];
                            }
                            $categoryDataArray['product'] = $data;
                            $bannerProductarray['category'][] = $categoryDataArray;
                        }
                    }
                }
            }
            if ($getBannerCategoryData['mastercategory_id'] != 0 && $getBannerCategoryData['maincategory_id'] != 0 && $getBannerCategoryData['category_id'] == 0 && $getBannerCategoryData['subcategory_id'] == 0) {
                $flag = 0;
                $mainCategoryCategoryData = MainCategoryCategory::where('maincategory_id', '=', $getBannerCategoryData['maincategory_id'])->get()->toArray();
                foreach ($mainCategoryCategoryData as $mainCategoryCategoryDataValue) {
                    $CategoryData = Category::where('id', '=', $mainCategoryCategoryDataValue['category_id'])->get()->toArray();
                    if (!empty($CategoryData)) {
                        $productCategoryData = ProductAllCategory::where('category_id', '=', $mainCategoryCategoryDataValue['category_id'])->get()->toArray();
                        $data = array();
                        foreach ($productCategoryData as $productCategoryDataValue) {
                            $getProductCategory = Product::where('id', '=', $productCategoryDataValue['product_id'])->where('isActive', '1')->get()->toArray();
                            foreach ($getProductCategory as $getCategoryValue) {
                                $productImage = ProductsImage::select()->where('product_id', $getCategoryValue['id'])->get()->toArray();
                                 $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                                if ($productCategoryDataValue['product_id'] == $getCategoryValue['id']) {
                                    $data[] = array(
                                        'product_id' => $getCategoryValue['id'],
                                        'product_image' => $baseUrl['base_url'] . $product_image,
                                        'product_name' => $getCategoryValue['product_name'],
                                        'product_price' => $getCategoryValue['product_price'],
                                        'point' => $getCategoryValue['point'],
                                        'sale' => $getCategoryValue['sale'],
                                        'sale_price' => isset($getCategoryValue['sale_price']) ? $getCategoryValue['sale_price'] : null,
                                        'packet' => isset($getCategoryValue['packet']) ? $getCategoryValue['packet'] : null,
                                        'quantity' => $getCategoryValue['quantity'],
                                    );
                                }
                            }
                            $flag++;
                        }
                        $categoryDataArray = array();
                        if (!empty($data)) {
                            $categoryDataArray['maincategory_id'] = $getBannerCategoryData['maincategory_id'];
                            foreach ($CategoryData as $CategoryDataValue) {
                                $categoryDataArray['maincategory_id'] = $CategoryDataValue['id'];
                                $categoryDataArray['category_name'] = $CategoryDataValue['category_name'];
                            }
                            $categoryDataArray['product'] = $data;
                            $bannerProductarray['category'][] = $categoryDataArray;
                        }
                    }
                }
                if ($flag == 0) {
                    $mainCategoryCategoryData = MainCategory::where('id', '=', $getBannerCategoryData['maincategory_id'])->where('isActive', '1')->get()->toArray();
                    foreach ($mainCategoryCategoryData as $mainCategoryCategoryDataValue) {
                        $productMainCategoryData = ProductAllCategory::where('maincategory_id', '=', $getBannerCategoryData['maincategory_id'])->get()->toArray();
                        $data = array();
                        foreach ($productMainCategoryData as $productMainCategoryDataValue) {
                            $getProductCategory = Product::where('id', '=', $productMainCategoryDataValue['product_id'])->where('isActive', '1')->get()->toArray();
                            foreach ($getProductCategory as $getCategoryValue) {
                                $productImage = ProductsImage::select()->where('product_id', $getCategoryValue['id'])->get()->toArray();
                                $product_image = str_replace('\\', '/', $productImage[0]['product_image']);

                                if ($productMainCategoryDataValue['product_id'] == $getCategoryValue['id']) {
                                    $data[] = array(
                                        'product_id' => $getCategoryValue['id'],
                                        'product_image' => $baseUrl['base_url'] . $product_image,
                                        'product_name' => $getCategoryValue['product_name'],
                                        'product_price' => $getCategoryValue['product_price'],
                                        'point' => $getCategoryValue['point'],
                                        'sale' => $getCategoryValue['sale'],
                                        'sale_price' => isset($getCategoryValue['sale_price']) ? $getCategoryValue['sale_price'] : null,
                                        'packet' => isset($getCategoryValue['packet']) ? $getCategoryValue['packet'] : null,
                                        'quantity' => $getCategoryValue['quantity'],
                                    );
                                }
                            }
                        }
                        $categoryDataArray = array();
                        if (!empty($data)) {
                            $categoryDataArray['maincategory_id'] = $getBannerCategoryData['maincategory_id'];
                            foreach ($mainCategoryCategoryData as $mainCategoryCategoryDataValue) {
                                $categoryDataArray['maincategory_id'] = $mainCategoryCategoryDataValue['id'];
                                $categoryDataArray['category_name'] = $mainCategoryCategoryDataValue['main_category_name'];
                            }
                            $categoryDataArray['product'] = $data;
                            $bannerProductarray['category'][] = $categoryDataArray;
                        }
                    }
                }
            }

            if ($getBannerCategoryData['mastercategory_id'] != 0 && $getBannerCategoryData['maincategory_id'] != 0 && $getBannerCategoryData['category_id'] != 0 && $getBannerCategoryData['subcategory_id'] == 0) {
                $flag = 0;
                $categorySubCategoryData = CategorySubCategory::where('category_id', '=', $getBannerCategoryData['category_id'])->get()->toArray();
                foreach ($categorySubCategoryData as $categorySubCategoryDataValue) {
                    $subCategory = SubCategory::where('id', '=', $categorySubCategoryDataValue['subcategory_id'])->where('isActive', '1')->get()->toArray();
                    if ($subCategory != 0) {
                        $subCategoryData = ProductAllCategory::where('subcategory_id', '=', $categorySubCategoryDataValue['subcategory_id'])->get()->toArray();
                        $data = array();
                        foreach ($subCategoryData as $subCategoryDataValue) {
                            $getSubProduct = Product::where('id', '=', $subCategoryDataValue['product_id'])->where('isActive', '1')->get()->toArray();
                            foreach ($getSubProduct as $getSubCategoryValue) {
                                $productImage = ProductsImage::select()->where('product_id', $getSubCategoryValue['id'])->get()->toArray();
                                $product_image = str_replace('\\', '/', $productImage[0]['product_image']);

                                if ($subCategoryDataValue['product_id'] == $getSubCategoryValue['id']) {
                                    $data[] = array(
                                        'product_id' => $getSubCategoryValue['id'],
                                        'product_image' => $baseUrl['base_url'] . $product_image,
                                        'product_name' => $getSubCategoryValue['product_name'],
                                        'product_price' => $getSubCategoryValue['product_price'],
                                        'point' => $getSubCategoryValue['point'],
                                        'sale' => $getSubCategoryValue['sale'],
                                        'sale_price' => isset($getSubCategoryValue['sale_price']) ? $getSubCategoryValue['sale_price'] : null,
                                        'packet' => isset($getSubCategoryValue['packet']) ? $getSubCategoryValue['packet'] : null,
                                        'quantity' => $getSubCategoryValue['quantity'],
                                    );
                                }
                            }
                            $flag++;
                        }

                        $categoryDataArray = array();
                        if (!empty($data)) {
                            $categoryDataArray['category_id'] = $getBannerCategoryData['category_id'];
                            foreach ($subCategory as $subCategoryValue) {
                                $categoryDataArray['subcategory_id'] = $subCategoryValue['id'];
                                $categoryDataArray['category_name'] = $subCategoryValue['sub_category_name'];
                            }
                            $categoryDataArray['product'] = $data;
                            $bannerProductarray['category'][] = $categoryDataArray;
                        }
                    }
                }
                if ($flag == 0) {
                    $categoryData = Category::where('id', '=', $getBannerCategoryData['category_id'])->where('isActive', '1')->get()->toArray();
                    foreach ($categoryData as $mainCategoryCategoryDataValue) {
                        $productCategoryData = ProductAllCategory::where('category_id', '=', $getBannerCategoryData['category_id'])->get()->toArray();
                        $data = array();
                        foreach ($productCategoryData as $productCategoryDataValue) {
                            $getProductCategory = Product::where('id', '=', $productCategoryDataValue['product_id'])->where('isActive', '1')->get()->toArray();
                            foreach ($getProductCategory as $getCategoryValue) {
                                $productImage = ProductsImage::select()->where('product_id', $getCategoryValue['id'])->get()->toArray();
                                $product_image = str_replace('\\', '/', $productImage[0]['product_image']);

                                if ($productCategoryDataValue['product_id'] == $getCategoryValue['id']) {
                                    $data[] = array(
                                        'product_id' => $getCategoryValue['id'],
                                        'product_image' => $baseUrl['base_url'] . $product_image,
                                        'product_name' => $getCategoryValue['product_name'],
                                        'product_price' => $getCategoryValue['product_price'],
                                        'point' => $getCategoryValue['point'],
                                        'sale' => $getCategoryValue['sale'],
                                        'sale_price' => isset($getCategoryValue['sale_price']) ? $getCategoryValue['sale_price'] : null,
                                        'packet' => isset($getCategoryValue['packet']) ? $getCategoryValue['packet'] : null,
                                        'quantity' => $getCategoryValue['quantity'],
                                    );
                                }
                            }
                        }
                        $categoryDataArray = array();
                        if (!empty($data)) {
                            $categoryDataArray['category_id'] = $getBannerCategoryData['category_id'];
                            foreach ($categoryData as $categoryDataValue) {
                                $categoryDataArray['category_id'] = $categoryDataValue['id'];
                                $categoryDataArray['category_name'] = $categoryDataValue['category_name'];
                            }
                            $categoryDataArray['product'] = $data;
                            $bannerProductarray['category'][] = $categoryDataArray;
                        }
                    }
                }
            }
            if ($getBannerCategoryData['mastercategory_id'] != 0 && $getBannerCategoryData['maincategory_id'] != 0 && $getBannerCategoryData['category_id'] != 0 && $getBannerCategoryData['subcategory_id'] != 0) {
                $subCategoryAllData = SubCategory::where('id', '=', $getBannerCategoryData['subcategory_id'])->where('isActive', '1')->get()->toArray();
                foreach ($subCategoryAllData as $key => $subCategoryAllDataValue) {
                    $productSubCategoryData = ProductAllCategory::where('subcategory_id', '=', $getBannerCategoryData['subcategory_id'])->get()->toArray();
                    $data = array();
                    foreach ($productSubCategoryData as $productSubCategoryDataValue) {
                        $getProductCategory = Product::where('id', '=', $productSubCategoryDataValue['product_id'])->where('isActive', '1')->get()->toArray();
                        foreach ($getProductCategory as $getAllSubCategoryValue) {
                            $productImage = ProductsImage::select()->where('product_id', $getAllSubCategoryValue['id'])->get()->toArray();
                            $product_image = str_replace('\\', '/', $productImage[0]['product_image']);


                            if ($productSubCategoryDataValue['product_id'] == $getAllSubCategoryValue['id']) {
                                $data[] = array(
                                    'product_id' => $getAllSubCategoryValue['id'],
                                    'product_image' => $baseUrl['base_url'] . $product_image,
                                    'product_name' => $getAllSubCategoryValue['product_name'],
                                    'product_price' => $getAllSubCategoryValue['product_price'],
                                    'point' => $getAllSubCategoryValue['point'],
                                    'sale' => $getAllSubCategoryValue['sale'],
                                    'sale_price' => isset($getAllSubCategoryValue['sale_price']) ? $getAllSubCategoryValue['sale_price'] : null,
                                    'packet' => isset($getAllSubCategoryValue['packet']) ? $getAllSubCategoryValue['packet'] : null,
                                    'quantity' => $getAllSubCategoryValue['quantity'],
                                );
                            }
                        }
                    }
                    $categoryDataArray = array();
                    if (!empty($data)) {
                        $categoryDataArray['category_id'] = $getBannerCategoryData['category_id'];
                        foreach ($subCategoryAllData as $subCategoryAllDataValue) {
                            $categoryDataArray['subcategory_id'] = $subCategoryAllDataValue['id'];
                            $categoryDataArray['category_name'] = $subCategoryAllDataValue['sub_category_name'];
                        }
                        $categoryDataArray['product'] = $data;
                        $bannerProductarray['category'][] = $categoryDataArray;
                    }
                }
            }

            $returnArray = array(
                'message' => 'Successfull',
                'messagecode' => 1,
            );

            if (isset($bannerProductarray['category'])) {
                $returnArray['bannerproductdata'] = $bannerProductarray;
            } else {
                $returnArray = array(
                    'message' => 'Successfull',
                    'messagecode' => 0,
                    'bannerproductdata' => 'No data found in Banner Product',
                );
            }
            return response($returnArray, 200);
        } else {
            $validation = Validator::make($request->all(), [
                'banner_id' => 'required',
            ]);

            if ($validation->fails()) {
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;
            }
        }
    }
}

?>