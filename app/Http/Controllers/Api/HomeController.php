<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BannerCategory;
use App\Models\Category;
use App\Models\CategorySubCategory;
use App\Models\Explore;
use App\Models\ExploreExploreCategory;
use App\Models\ExploreProductOffer;
use App\Models\ExploreProductOfferProduct;
use App\Models\MainCategory;
use App\Models\MainCategoryCategory;
use App\Models\MasterCategory;
use App\Models\MasterMainCategory;
use App\Models\Product;
use App\Models\ProductAllCategory;
use App\Models\ProductsImage;
use App\Models\SubCategory;

class HomeController extends Controller
{

    public function home()
    {

        /*get banner data*/

        $getBanner = Banner::select()->where('status', 1)->get();
        $baseUrl = \Config::get('baseurl');

        $bannerData = array();

        foreach ($getBanner as $key => $value) {
            $banner_image = str_replace('\\', '/', $value->banner_image);
            $bannerData[] = array(
                'id' => $value->id,
                'banner_image' => $baseUrl['base_url'] . $banner_image,
            );
        }

        /*get banner category data*/

        $bdata = BannerCategory::select('bannercategory.*', 'mastercategory.master_category_name', 'mastercategory.master_category_image', 'maincategory.main_category_name', 'maincategory.main_category_image', 'category.category_name', 'subcategory.sub_category_name', 'category.category_image', 'subcategory.sub_category_image')
            ->leftJoin('mastercategory', 'mastercategory.id', '=', 'bannercategory.mastercategory_id')
            ->leftJoin('maincategory', 'maincategory.id', '=', 'bannercategory.maincategory_id')
            ->leftJoin('category', 'category.id', '=', 'bannercategory.category_id')
            ->leftJoin('subcategory', 'subcategory.id', '=', 'bannercategory.subcategory_id')
            ->get()->toArray();

        $bannerCategoryData = array();
        $increment = 0;

        //Condition for checking wether the category is in the table or not
        foreach ($bdata as $key => $getExplore) {
            $category = '';
            $name = '';
            $image = '';
            $ids = '';
            if (isset($getExplore['mastercategory_id']) && $getExplore['mastercategory_id'] != 0) {
                $category = 'mastercategory';
                $master_data = MasterCategory::where('id', '=', $getExplore['mastercategory_id'])->get()->toArray();
                $category_image = str_replace('\\', '/', $master_data[0]['master_category_image']);

                $data = array();

                $data = array(

                    "mastercategory_id" => $getExplore['mastercategory_id'],
                    "category_name" => $master_data[0]['master_category_name'],
                    "category_image" => $baseUrl['base_url'] . $category_image,
                );

            }
            if (isset($getExplore['maincategory_id']) && $getExplore['maincategory_id'] != 0) {
                $category = 'maincategory';

                $main_data = MasterMainCategory::select('mastermaincategory.*', 'maincategory.*')
                    ->Join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
                    ->where('mastermaincategory.maincategory_id', '=', $getExplore['maincategory_id'])->get()->toArray();
                $main_category_image = str_replace('\\', '/', $main_data[0]['main_category_image']);
                $data = array();

                $data = array(
                    "mastercategory_id" => $main_data[0]['mastercategory_id'],
                    "maincategory_id" => $main_data[0]['maincategory_id'],
                    "category_name" => $main_data[0]['main_category_name'],
                    "category_image" => $baseUrl['base_url'] . $main_category_image,
                );
            }
            if (isset($getExplore['category_id']) && $getExplore['category_id'] != 0) {
                $category = 'category';
                $main_data = MainCategoryCategory::select('maincategorycategory.*', 'category.*')
                    ->Join('category', 'category.id', '=', 'maincategorycategory.category_id')
                    ->where('maincategorycategory.category_id', '=', $getExplore['category_id'])->get()->toArray();
                $category_image = str_replace('\\', '/', $main_data[0]['category_image']);
                $data = array();

                $data = array(
                    "mastercategory_id" => $getExplore['mastercategory_id'],
                    "maincategory_id" => $main_data[0]['maincategory_id'],
                    "category_id" => $main_data[0]['category_id'],
                    "category_name" => $main_data[0]['category_name'],
                    "category_image" => $baseUrl['base_url'] . $category_image,
                );
            }

            if (isset($getExplore['subcategory_id']) && $getExplore['subcategory_id'] != 0) {
                $category = 'subcategory';
                $main_data = CategorySubCategory::select('categorysubcategory.*', 'subcategory.*')
                    ->Join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
                    ->where('categorysubcategory.subcategory_id', '=', $getExplore['subcategory_id'])->get()->toArray();
                $sub_category_image = str_replace('\\', '/', $main_data[0]['sub_category_image']);
                $data = array();

                $data = array(
                    "mastercategory_id" => $getExplore['mastercategory_id'],
                    "maincategory_id" => $getExplore['maincategory_id'],
                    "category_id" => $main_data[0]['category_id'],
                    "subcategory_id" => $main_data[0]['subcategory_id'],
                    "category_name" => $main_data[0]['sub_category_name'],
                    "category_image" => $baseUrl['base_url'] . $sub_category_image,
                );
            }
            $bannerCategoryData[$increment] = $data;
            $increment++;
        }

        /*get explore data*/

        $getExplore = Explore::select('id', 'explore_name', 'explore_details')->get()->toArray();
        $exploreCategoryData = array();

        foreach ($getExplore as $key1 => $getExploreData) {
            $exploreData = array();
            $exploreData['explore_id'] = $getExploreData['id'];
            $exploreData['explore_name'] = $getExploreData['explore_name'];
            $exploreData['explore_details'] = $getExploreData['explore_details'];

            $getExploreDetails = ExploreExploreCategory::where('exploreexplorecategory.explore_id', $getExploreData['id'])
                ->get()->toArray();

            $categoryDataArray = array();
            foreach ($getExploreDetails as $key => $exploreCategory) {
                $exploreCategoryarray = array();
                $exploreCategoryarray['mastercategory_id'] = $exploreCategory['mastercategory_id'];
                $exploreCategoryarray['maincategory_id'] = $exploreCategory['maincategory_id'];
                $exploreCategoryarray['category_id'] = $exploreCategory['category_id'];
                $exploreCategoryarray['subcategory_id'] = $exploreCategory['subcategory_id'];

                if ($exploreCategory['mastercategory_id'] != 0 && $exploreCategory['maincategory_id'] == 0 && $exploreCategory['category_id'] == 0 && $exploreCategory['subcategory_id'] == 0) {

                    $masterData = MasterCategory::where('id', '=', $exploreCategory['mastercategory_id'])->select('master_category_name', 'master_category_image')->get()->toArray();
                    $master_category_image = str_replace('\\', '/', $masterData[0]['master_category_image']);
                    $MasterCategoryData = array();

                    $MasterCategoryData = array(
                        'category_name' => $masterData[0]['master_category_name'],
                        'category_image' => $baseUrl['base_url'] . $master_category_image,
                    );
                    if (count($masterData) > 0) {
                        $exploreCategoryarray['category_data'] = $MasterCategoryData;
                    }

                } elseif ($exploreCategory['mastercategory_id'] != 0 && $exploreCategory['maincategory_id'] != 0 && $exploreCategory['category_id'] == 0 && $exploreCategory['subcategory_id'] == 0) {

                    $mainData = MainCategory::where('id', '=', $exploreCategory['maincategory_id'])->select('main_category_name', 'main_category_image')->get()->toArray();
                    $main_category_image = str_replace('\\', '/', $mainData[0]['main_category_image']);
                    $MainCategoryData = array();

                    $MainCategoryData = array(
                        'category_name' => $mainData[0]['main_category_name'],
                        'category_image' => $baseUrl['base_url'] . $main_category_image,
                    );

                    if (count($mainData) > 0) {
                        $exploreCategoryarray['category_data'] = $MainCategoryData;
                    }

                } elseif ($exploreCategory['mastercategory_id'] != 0 && $exploreCategory['maincategory_id'] != 0 && $exploreCategory['category_id'] != 0 && $exploreCategory['subcategory_id'] == 0) {

                    $categoryData = Category::where('id', '=', $exploreCategory['category_id'])->select('category_name', 'category_image')->get()->toArray();
                    $category_image = str_replace('\\', '/', $categoryData[0]['category_image']);
                    $CategoryData = array();

                    $CategoryData = array(
                        'category_name' => $categoryData[0]['category_name'],
                        'category_image' => $baseUrl['base_url'] . $category_image,
                    );
                    if (count($categoryData) > 0) {
                        $exploreCategoryarray['category_data'] = $CategoryData;
                    }

                } elseif ($exploreCategory['mastercategory_id'] != 0 && $exploreCategory['maincategory_id'] != 0 && $exploreCategory['category_id'] != 0 && $exploreCategory['subcategory_id'] != 0) {

                    $subcategoryData = SubCategory::where('id', '=', $exploreCategory['subcategory_id'])->select('sub_category_name', 'sub_category_image')->get()->toArray();
                    $sub_category_image = str_replace('\\', '/', $subcategoryData[0]['sub_category_image']);
                    $SubCategoryData = array();

                    $SubCategoryData = array(
                        'category_name' => $subcategoryData[0]['sub_category_name'],
                        'category_image' => $baseUrl['base_url'] . $sub_category_image,
                    );

                    if (count($subcategoryData) > 0) {
                        $exploreCategoryarray['category_data'] = $SubCategoryData;
                    }

                }

                $categoryDataArray[] = $exploreCategoryarray;
            }

            $exploreData['categories'] = $categoryDataArray;
            $exploreCategoryData[] = $exploreData;

        }

        /*get category wise product data*/
        $bannerCategoryProducts = array();

        $getBannerCategoryData = BannerCategory::select('bannercategory.*', 'mastercategory.master_category_name', 'mastercategory.master_category_image', 'maincategory.main_category_name', 'maincategory.main_category_image', 'category.category_name', 'subcategory.sub_category_name', 'category.category_image', 'subcategory.sub_category_image')
            ->leftJoin('mastercategory', 'mastercategory.id', '=', 'bannercategory.mastercategory_id')
            ->leftJoin('maincategory', 'maincategory.id', '=', 'bannercategory.maincategory_id')
            ->leftJoin('category', 'category.id', '=', 'bannercategory.category_id')
            ->leftJoin('subcategory', 'subcategory.id', '=', 'bannercategory.subcategory_id')
            ->get()->toArray();

        $getProduct = Product::get()->where('status', 1)->toArray();
        foreach ($getBannerCategoryData as $key => $bannerCategory) {

            $flag = 0;
            $masterCategoryProducts = array();
            $masterCategoryProductName = array();
            $masterCategoryProductCount = array();
            $mainCategoryProducts = array();
            $mainCategoryProductName = array();
            $mainCategoryProductCount = array();
            $categoryProducts = array();
            $categoryProductName = array();
            $categoryProductCount = array();
            $subCategoryProducts = array();
            $subCategoryProductName = array();
            $subCategoryProductCount = array();

            if ($bannerCategory['mastercategory_id'] != 0 && $bannerCategory['maincategory_id'] == 0 && $bannerCategory['category_id'] == 0 && $bannerCategory['subcategory_id'] == 0) {
                $masterCategoryData = ProductAllCategory::where('mastercategory_id', '=', $bannerCategory['mastercategory_id'])->limit(10)->get()->toArray();
                $masterCategoryDataCount = ProductAllCategory::where('mastercategory_id', '=', $bannerCategory['mastercategory_id'])->count();
                $data = array();
                if (count($masterCategoryData) > 0) {
                    foreach ($masterCategoryData as $key1 => $mastersCategoryData) {
                        foreach ($getProduct as $key2 => $getmastercategoryvalue1) {
                            $productImage = ProductsImage::select()->where('product_id', $getmastercategoryvalue1['id'])->get()->toArray();
                            $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                            if ($mastersCategoryData['product_id'] == $getmastercategoryvalue1['id']) {
                                $data[] = array(
                                    'product_id' => $getmastercategoryvalue1['id'],
                                    'product_image' => $baseUrl['base_url'] . $product_image,
                                    'product_name' => $getmastercategoryvalue1['product_name'],
                                    'product_price' => $getmastercategoryvalue1['product_price'],
                                    'point' => $getmastercategoryvalue1['point'],
                                    'sale' => $getmastercategoryvalue1['sale'],
                                    'sale_price' => isset($getmastercategoryvalue1['sale_price']) ? $getmastercategoryvalue1['sale_price'] : null,
                                    'packet' => isset($getmastercategoryvalue1['packet']) ? $getmastercategoryvalue1['packet'] : null,
                                    'quantity' => $getmastercategoryvalue1['quantity'],
                                );
                            }
                        }
                    }
                }
                if (count($data) > 0) {
                    $masterCategoryProducts = $data;
                    $masterCategoryProductCount = $masterCategoryDataCount;
                    $masterCategoryProductName = $bannerCategory['master_category_name'];
                    $flag = $flag + 1;
                }
            }

            if ($bannerCategory['mastercategory_id'] != 0 && $bannerCategory['maincategory_id'] != 0 && $bannerCategory['category_id'] == 0 && $bannerCategory['subcategory_id'] == 0) {
                $mainData = ProductAllCategory::where('maincategory_id', '=', $bannerCategory['maincategory_id'])->limit(10)->get()->toArray();
                $mainDataCount = ProductAllCategory::where('maincategory_id', '=', $bannerCategory['maincategory_id'])->count();
                $data = array();
                foreach ($mainData as $key1 => $mainsData) {
                    foreach ($getProduct as $key2 => $getmaincategoryvalue1) {
                        $productImage = ProductsImage::select()->where('product_id', $getmaincategoryvalue1['id'])->get()->toArray();
                        $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                        if ($mainsData['product_id'] == $getmaincategoryvalue1['id']) {

                            $data[] = array(
                                'product_id' => $getmaincategoryvalue1['id'],
                                'product_image' => $baseUrl['base_url'] . $product_image,
                                'product_name' => $getmaincategoryvalue1['product_name'],
                                'product_price' => $getmaincategoryvalue1['product_price'],
                                'point' => $getmaincategoryvalue1['point'],
                                'sale' => $getmaincategoryvalue1['sale'],
                                'sale_price' => isset($getmaincategoryvalue1['sale_price']) ? $getmaincategoryvalue1['sale_price'] : null,
                                'packet' => isset($getmaincategoryvalue1['packet']) ? $getmaincategoryvalue1['packet'] : null,
                                'quantity' => $getmaincategoryvalue1['quantity'],
                            );
                        }
                    }
                }
                if (count($data) > 0) {
                    $mainCategoryProducts = $data;
                    $mainCategoryProductCount = $mainDataCount;
                    $mainCategoryProductName = $bannerCategory['main_category_name'];
                    $flag = $flag + 1;
                }
            }

            if ($bannerCategory['mastercategory_id'] != 0 && $bannerCategory['maincategory_id'] != 0 && $bannerCategory['category_id'] != 0 && $bannerCategory['subcategory_id'] == 0) {
                $categoryData = ProductAllCategory::where('category_id', '=', $bannerCategory['category_id'])->limit(10)->get()->toArray();
                $categoryDataCount = ProductAllCategory::where('category_id', '=', $bannerCategory['category_id'])->count();

                $data = array();

                foreach ($categoryData as $key1 => $categorysData) {
                    foreach ($getProduct as $key2 => $getcategoryvalue1) {
                        $productImage = ProductsImage::select()->where('product_id', $getcategoryvalue1['id'])->get()->toArray();
                        $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                        if ($categorysData['product_id'] == $getcategoryvalue1['id']) {
                            $data[] = array(
                                'product_id' => $getcategoryvalue1['id'],
                                'product_image' => $baseUrl['base_url'] . $product_image,
                                'product_name' => $getcategoryvalue1['product_name'],
                                'product_price' => $getcategoryvalue1['product_price'],
                                'point' => $getcategoryvalue1['point'],
                                'sale' => $getcategoryvalue1['sale'],
                                'sale_price' => isset($getcategoryvalue1['sale_price']) ? $getcategoryvalue1['sale_price'] : null,
                                'packet' => isset($getcategoryvalue1['packet']) ? $getcategoryvalue1['packet'] : null,
                                'quantity' => $getcategoryvalue1['quantity'],
                            );
                        }
                    }
                }
                if (count($data) > 0) {
                    $categoryProducts = $data;
                    $categoryProductCount = $categoryDataCount;
                    $categoryProductName = $bannerCategory['category_name'];
                    $flag = $flag + 1;
                }
            }

            if ($bannerCategory['mastercategory_id'] != 0 && $bannerCategory['maincategory_id'] != 0 && $bannerCategory['category_id'] != 0 && $bannerCategory['subcategory_id'] != 0) {
                $subcategoryData = ProductAllCategory::where('subcategory_id', '=', $bannerCategory['subcategory_id'])->limit(10)->get()->toArray();
                $subCategoryDataCount = ProductAllCategory::where('subcategory_id', '=', $bannerCategory['subcategory_id'])->count();

                $data = array();

                foreach ($subcategoryData as $key1 => $subcategorysData) {
                    foreach ($getProduct as $key2 => $getsubcategoryvalue1) {
                        $productImage = ProductsImage::select()->where('product_id', $getsubcategoryvalue1['id'])->get()->toArray();
                        $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                        if ($subcategorysData['product_id'] == $getsubcategoryvalue1['id']) {
                            $data[] = array(
                                'product_id' => $getsubcategoryvalue1['id'],
                                'product_image' => $baseUrl['base_url'] . $product_image,
                                'product_name' => $getsubcategoryvalue1['product_name'],
                                'product_price' => $getsubcategoryvalue1['product_price'],
                                'point' => $getsubcategoryvalue1['point'],
                                'sale' => $getsubcategoryvalue1['sale'],
                                'sale_price' => isset($getsubcategoryvalue1['sale_price']) ? $getsubcategoryvalue1['sale_price'] : null,
                                'packet' => isset($getsubcategoryvalue1['packet']) ? $getsubcategoryvalue1['packet'] : null,
                                'quantity' => $getsubcategoryvalue1['quantity'],
                            );
                        }
                    }
                }
                if (count($data) > 0) {
                    $subCategoryProducts = $data;
                    $subCategoryProductCount = $subCategoryDataCount;
                    $subCategoryProductName = $bannerCategory['sub_category_name'];
                    $flag = $flag + 1;
                }
            }

            if ($flag > 0) {
                $bannerCategoryProducts[$key]['mastercategory_id'] = $bannerCategory['mastercategory_id'];
                $bannerCategoryProducts[$key]['maincategory_id'] = $bannerCategory['maincategory_id'];
                $bannerCategoryProducts[$key]['category_id'] = $bannerCategory['category_id'];
                $bannerCategoryProducts[$key]['subcategory_id'] = $bannerCategory['subcategory_id'];
                if (count($masterCategoryProducts) > 0) {
                    $bannerCategoryProducts[$key]['category_name'] = $masterCategoryProductName;
                    $bannerCategoryProducts[$key]['products_count'] = $masterCategoryProductCount;
                    $bannerCategoryProducts[$key]['products'] = $masterCategoryProducts;
                }
                if (count($mainCategoryProducts) > 0) {
                    $bannerCategoryProducts[$key]['category_name'] = $mainCategoryProductName;
                    $bannerCategoryProducts[$key]['products_count'] = $mainCategoryProductCount;
                    $bannerCategoryProducts[$key]['products'] = $mainCategoryProducts;
                }
                if (count($categoryProducts) > 0) {
                    $bannerCategoryProducts[$key]['category_name'] = $categoryProductName;
                    $bannerCategoryProducts[$key]['products_count'] = $categoryProductCount;
                    $bannerCategoryProducts[$key]['products'] = $categoryProducts;
                }
                if (count($subCategoryProducts) > 0) {
                    $bannerCategoryProducts[$key]['category_name'] = $subCategoryProductName;
                    $bannerCategoryProducts[$key]['products_count'] = $subCategoryProductCount;
                    $bannerCategoryProducts[$key]['products'] = $subCategoryProducts;
                }
            }
        }

        /*get offer product and product data*/

        $getExploreProductOffer = ExploreProductOffer::select()->where('status', 1)->get();

        $exploreProductOfferData = array();

        foreach ($getExploreProductOffer as $key => $value) {

            $getExploreProductOfferProduct = ExploreProductOfferProduct::select('exploreproductofferproduct.*', 'products.*', 'exploreproductoffer.*')
                ->join('products', 'products.id', '=', 'exploreproductofferproduct.product_id')
                ->join('exploreproductoffer', 'exploreproductoffer.id', '=', 'exploreproductofferproduct.exploreproductoffer_id')
                ->where('exploreproductofferproduct.exploreproductoffer_id', $value->id)
                ->limit(5)
                ->get()
                ->toArray();

            $offerProductCount = ExploreProductOfferProduct::select('exploreproductofferproduct.*', 'products.*', 'exploreproductoffer.*')
                ->join('products', 'products.id', '=', 'exploreproductofferproduct.product_id')
                ->join('exploreproductoffer', 'exploreproductoffer.id', '=', 'exploreproductofferproduct.exploreproductoffer_id')
                ->where('exploreproductofferproduct.exploreproductoffer_id', $value->id)
                ->count();

            $productData = array();

            foreach ($getExploreProductOfferProduct as $getProductData) {

                $productImage = ProductsImage::select()->where('product_id', $getProductData['product_id'])->get()->toArray();
                $product_image = str_replace('\\', '/', $productImage[0]['product_image']);
                $productData[] = array(
                    "product_id" => $getProductData['product_id'],
                    "product_name" => $getProductData['product_name'],
                    "product_price" => $getProductData['product_price'],
                    "sale" => $getProductData['sale'],
                    "sale_price" => isset($getProductData['sale_price']) ? $getProductData['sale_price'] : null,
                    "packet" => isset($getProductData['packet']) ? $getProductData['packet'] : null,
                    "quantity" => $getProductData['quantity'],
                    "product_image" => $baseUrl['base_url'] . $product_image,
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

        return response(['banner' => $bannerData,
            'bannercategory' => $bannerCategoryData,
            'explorecategory' => $exploreCategoryData,
            'bannercategoryproducts' => $bannerCategoryProducts,
            'exploreproductoffer' => $exploreProductOfferData,
            'message' => 'Successful',
            'status' => 200], 200);
    }

}
