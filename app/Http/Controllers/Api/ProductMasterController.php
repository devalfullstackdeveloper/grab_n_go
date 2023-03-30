<?php

namespace App\Http\Controllers\Api;

use id;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Models\MasterCategory;
use App\Models\ProductCategory;
use App\Models\MasterMainCategory;
use App\Models\ProductSubCategory;
use App\Models\CategorySubCategory;
use App\Models\ProductMainCategory;
use App\Http\Controllers\Controller;
use App\Models\MainCategoryCategory;
use App\Models\ProductMasterCategory;
use App\Models\ProductsImage;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;


class ProductMasterController extends Controller
{
            //Product From Master Category
          public function productFromMaster(Request $request)
          {
                    $data = array();
                    if(isset($request->master_cat_id))
                    {
                        $getmasterDetails= MasterCategory::select()->where('mastercategory.id',$request->master_cat_id)->first()->toArray();
                        $data['master_id'] = $getmasterDetails['id'];
                        $data['master_name'] = $getmasterDetails['master_category_name'];
                        $getmastermainDetails = MasterMainCategory::join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
                                        ->where('mastermaincategory.mastercategory_id', $request->master_cat_id)
                                        ->get(['mastermaincategory.maincategory_id', 'maincategory.main_category_name'])->toArray();
                        foreach($getmastermainDetails as $key=>$value)
                        {
                            $data['main_category'][$key]=$value;
                            $getProductDetails = ProductMainCategory::join('products', 'products.id', '=', 'productsmaincategory.product_id')
                                        ->where('productsmaincategory.maincategory_id', $value['maincategory_id'])
                                        ->get(['productsmaincategory.maincategory_id', 'products.id','products.product_name','products.product_price','products.point'])->toArray();
                            foreach($getProductDetails as $key1=>$value1)
                            {
                              $productImage = ProductsImage::select()->where('product_id',$value1['id'])->get()->toArray();

                                        $data['main_category'][$key]['product_detail'][$key1]=array(
                                          'product_id' =>$value1['id'],
                                          'product_name' =>$value1['product_name'],
                                          'product_image' =>$productImage[0]['product_image'],
                                          'product_price' =>$value1['product_price'],
                                          'point' =>$value1['point']
                                        );
                            }
                        }
                       
                        return response([
                               //'message' => ' List of all Products are found.',
                               'productdata' => $data],
                                200);
                    }
          }

            //Product From Main Category 
          public function productFromMain(Request $request)
          {
                    $data = array();
                    if(isset($request->main_cat_id))
                    {
                        $getmainDetails= MainCategory::select()->where('maincategory.id',$request->main_cat_id)->first()->toArray();
                        $data['main_id'] = $getmainDetails['id'];
                        $data['main_name'] = $getmainDetails['main_category_name'];
                        $getmaincategoryDetails = MainCategoryCategory::join('category', 'category.id', '=', 'maincategorycategory.category_id')
                                        ->where('maincategorycategory.maincategory_id', $request->main_cat_id)
                                        ->get(['maincategorycategory.category_id', 'category.category_name'])->toArray();  
                                                
                        foreach($getmaincategoryDetails as $key=>$value)
                        {
                            $data['category'][$key]=$value;
                            $getProductsDetails = ProductCategory::join('products', 'products.id', '=','productscategory.product_id')
                                          ->where('productscategory.category_id', $value['category_id'])
                                          ->get(['productscategory.category_id','products.id','products.product_name','products.product_price','products.point'])->toArray(); 
                                                  
                                           
                            foreach($getProductsDetails as $key2=>$value2)
                           {
                             $productImage = ProductsImage::select()->where('product_id',$value2['id'])->get()->toArray();
                             
                                    
                                     $data['category'][$key]['product_detail'][$key2]=array(
                                          'product_id' =>$value2['id'],
                                          'product_name' =>$value2['product_name'],
                                          'product_image' =>$productImage[0]['product_image'],
                                          'product_price' =>$value2['product_price'],
                                          'point' =>$value2['point']
                                         );
                           }
                        }           
                            
                        return response([
                              //'message' => ' List of all Products are found.',
                              'productdata' => $data],
                               200);                       
                    }
          } 

          
             //Product From Category 
          public function productFromCategory(Request $request)
          {
                    $data = array();
                    if(isset($request->cat_id))
                    {
                       $getcategoryDetails=Category::select()->where('category.id',$request->cat_id)->first()->toArray();
                       $data['cate_id']=$getcategoryDetails['id'];
                       $data['cate_name']=$getcategoryDetails['category_name'];
                       $getcategorysDetails = CategorySubCategory::join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
                                      ->where('categorysubcategory.category_id', $request->cat_id)
                                      ->get(['categorysubcategory.subcategory_id','subcategory.sub_category_name'])->toArray();
                       
                        foreach($getcategorysDetails as $key=>$value)
                        { 
                           $data['subcategory'][$key]=$value;
                           $getProductsDetails = ProductSubCategory::join('products', 'products.id', '=','productssubcategory.product_id')
                                         ->where('productssubcategory.subcategory_id', $value['subcategory_id'])
                                         ->get(['productssubcategory.subcategory_id','products.id','products.product_name','products.product_price','products.point'])->toArray();         
                        
                           foreach($getProductsDetails as $key3=>$value3)
                          {
                            $productImage = ProductsImage::select()->where('product_id',$value3['id'])->get()->toArray();

                                    $data['subcategory'][$key]['product_detail'][$key3]=array(
                                         'product_id' =>$value3['id'],
                                         'product_name' =>$value3['product_name'],
                                         'product_image' =>$productImage[0]['product_image'],
                                         'product_price' =>$value3['product_price'],
                                         'point' =>$value3['point']
                                        );     
                           }                        
                      }
                       
                      return response([
                             //'message' => ' List of all Products are found.',
                            'productdata' => $data],
                             200);
                    }
           }
             
}

?>