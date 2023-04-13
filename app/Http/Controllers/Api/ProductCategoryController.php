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


class ProductCategoryController extends Controller
{
          /*Get product from master category data*/
          public function productFromMaster(Request $request)
          {
	                  $baseUrl= \Config::get('baseurl');
                    $data = array();
                         if(isset($request->mastercategory_id))
                      {
                                  $getmasterDetails= MasterCategory::select()->where('mastercategory.id',$request->mastercategory_id)->first()->toArray();
                                  $data['mastercategory_id'] = $getmasterDetails['id'];
                                  $data['mastercategory_name'] = $getmasterDetails['master_category_name'];
                                  $getmastermainDetails = MasterMainCategory::join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
                                                  ->where('mastermaincategory.mastercategory_id', $request->mastercategory_id)
                                                  ->get(['mastermaincategory.maincategory_id', 'maincategory.main_category_name'])->toArray();
                                
                        foreach($getmastermainDetails as $key=>$getmastercategoryvalue)
                        {  
                                  $getProductDetails = ProductMainCategory::join('products', 'products.id', '=', 'productsmaincategory.product_id')
                                        ->where('productsmaincategory.maincategory_id', $getmastercategoryvalue['maincategory_id'])
                                        ->where('products.status',1)
                                        ->limit(10)
                                        ->get(['productsmaincategory.maincategory_id', 'products.id','products.product_name','products.product_price','products.point','products.quantity','products.sale','products.sale_price'])->toArray();
                        
                                   $getProductCount = ProductMainCategory::join('products', 'products.id', '=', 'productsmaincategory.product_id')
                                        ->where('productsmaincategory.maincategory_id', $getmastercategoryvalue['maincategory_id'])
                                        ->where('products.status',1)
                                        ->count();
                                        
                                        if($getProductCount){
                                          $getmastercategoryvalue['product_count'] = $getProductCount;                          
                                        }
                                          
                                   $data['main_category'][$key]=$getmastercategoryvalue;                                          
                                         
                        foreach($getProductDetails as $key1=>$getmastercategoryvalue1)
                        {
                              $productImage = ProductsImage::select()->where('product_id',$getmastercategoryvalue1['id'])->get()->toArray();

                               $data['main_category'][$key]['product'][$key1]=array(                                         
                                         
                                  'product_id' =>$getmastercategoryvalue1['id'],
                                  'product_image' =>$baseUrl['base_url'].$productImage[0]['product_image'],
                                  'product_name' =>$getmastercategoryvalue1['product_name'],
                                  'product_price' =>$getmastercategoryvalue1['product_price'],
                                  'point' =>$getmastercategoryvalue1['point'],
                                  'sale'=>$getmastercategoryvalue1['sale'],
                                  'sale_price'=>isset($getmastercategoryvalue1['sale_price']) ? $getmastercategoryvalue1['sale_price'] : '0' ,
                                  'quantity'=>$getmastercategoryvalue1['quantity']
                                        );
                        }
                        }
                       
                        return response([
                              'mastercategory' => $data,
                              'message' => 'List of all master category product.',],
                                200);
                        }else{
                              $validation = Validator::make($request->all(), [
                              'mastercategory_id' => 'required',
                        ]);

                        if($validation->fails()){
                            $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                            return $fieldsWithErrorMessagesArray;
                        } 
                     }
          }


          
          
             /*Get product from main category data*/
          public function productFromMain(Request $request)
          {
	                  $baseUrl= \Config::get('baseurl');
                    $data = array();
                          if(isset($request->maincategory_id))
                      {
                                   $getmainDetails= MainCategory::select()->where('maincategory.id',$request->maincategory_id)->first()->toArray();
                                   $data['main_category_id'] = $getmainDetails['id'];
                                   $data['main_category_name'] = $getmainDetails['main_category_name'];

                                   if(isset($getmainDetails['main_category_banner_image']) != null)
                                   {
                                    $data['main_category_banner_image'] = $baseUrl['base_url'].$getmainDetails['main_category_banner_image'];
                                   }else{
                                    $data['main_category_banner_image'] = null;
                                   }
                                   
                                   $getmaincategoryDetails = MainCategoryCategory::join('category', 'category.id', '=', 'maincategorycategory.category_id')
                                                    ->where('maincategorycategory.maincategory_id', $request->maincategory_id)
                                                    ->get(['maincategorycategory.category_id', 'category.category_name'])->toArray(); 
                                                
                          foreach($getmaincategoryDetails as $key=>$getmaincategoryvalue)
                        {
                                  $getProductsDetails = ProductCategory::join('products', 'products.id', '=','productscategory.product_id')
                                          ->where('productscategory.category_id', $getmaincategoryvalue['category_id'])
                                           ->where('products.status',1)
                                          ->limit(10)
                                          ->get(['productscategory.category_id','products.id','products.product_name','products.product_price','products.point','products.quantity','products.sale','products.sale_price'])->toArray(); 
                                              
                                  $getProductCount = ProductCategory::join('products', 'products.id', '=','productscategory.product_id')
                                          ->where('productscategory.category_id', $getmaincategoryvalue['category_id'])
                                           ->where('products.status',1)
                                          ->count();           
                                          
                                          if($getProductCount){
                                            $getmaincategoryvalue['product_count'] = $getProductCount;                          
                                          }
                               
                                  $data['category'][$key]=$getmaincategoryvalue;

                          foreach($getProductsDetails as $key2=>$getmaincategoryvalue2)
                          {
                                  $productImage = ProductsImage::select()->where('product_id',$getmaincategoryvalue2['id'])->get()->toArray();
                             
                                     $data['category'][$key]['product'][$key2]=array(
                                          'product_id' =>$getmaincategoryvalue2['id'],
                                          'product_image' =>$baseUrl['base_url'].$productImage[0]['product_image'],
                                          'product_name' =>$getmaincategoryvalue2['product_name'],
                                          'product_price' =>$getmaincategoryvalue2['product_price'],
                                          'point' =>$getmaincategoryvalue2['point'],
                                          'sale'=>$getmaincategoryvalue2['sale'],
                                          'sale_price'=>isset($getmaincategoryvalue2['sale_price']) ? $getmaincategoryvalue2['sale_price'] : null ,
                                          'quantity'=>$getmaincategoryvalue2['quantity']
                                         );
                          }
                        }           
                            
                        return response([
                              'maincategory' => $data,
                              'message' => 'List of all main category product.'],
                               200);                       
                      }else{
                        
                      $validation = Validator::make($request->all(), [
                      'maincategory_id' => 'required',
                       ]);
  
                      if($validation->fails()){
                          $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                          return $fieldsWithErrorMessagesArray;
                    } 
                  }
          }



                  
            /*Get product from category data*/
          public function productFromCategory(Request $request)
          {
	                  $baseUrl= \Config::get('baseurl');
                    $data = array();
                          if(isset($request->category_id))
                     {
                                   $getcategoryDetails=Category::select()->where('category.id',$request->category_id)->first()->toArray();
                                   $data['category_id']=$getcategoryDetails['id'];
                                   $data['category_name']=$getcategoryDetails['category_name'];
                                   
                                   if(isset($getcategoryDetails['category_banner_image'])!=null){
                                    $data['category_banner_image']=$baseUrl['base_url'].$getcategoryDetails['category_banner_image'];}
                                    else{
                                      $data['category_banner_image']=null; 
                                    }  
                                   $getcategorysDetails = CategorySubCategory::join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
                                                    ->where('categorysubcategory.category_id', $request->category_id)
                                                    ->get(['categorysubcategory.subcategory_id','subcategory.sub_category_name'])->toArray(); 
                                 
                       /* Get Products From Category*/
                                  $getProductfromCategory = ProductCategory::join('products', 'products.id', '=', 'productscategory.product_id')  
                                                    ->where('productscategory.category_id', $request->category_id) 
                                                    ->where('products.status',1)
                                                    ->get(['products.id','products.product_name','products.product_price','products.point','products.quantity','products.sale','products.sale_price'])->toArray();
                        
                                   $getProductfromCategoryCount = ProductCategory::join('products', 'products.id', '=', 'productscategory.product_id')  
                                                    ->where('productscategory.category_id', $request->category_id) 
                                                    ->where('products.status',1)
                                                    ->count();
                                             
                                   $data['product_count']=$getProductfromCategoryCount;
                                                      
                                            foreach($getProductfromCategory as $getProductfromCategoryData){
                                                  $productImage = ProductsImage::select()->where('product_id',$getProductfromCategoryData['id'])->get()->toArray();
                                                  $data['product'][]= array(
                                                    'product_id' =>$getProductfromCategoryData['id'],
                                                    'product_image' => $baseUrl['base_url'].$productImage[0]['product_image'],
                                                    'product_name' =>$getProductfromCategoryData['product_name'],
                                                    'product_price' =>$getProductfromCategoryData['product_price'],
                                                    'point' =>$getProductfromCategoryData['point'],
                                                    'sale'=>$getProductfromCategoryData['sale'],
                                                    'sale_price'=>isset($getProductfromCategoryData['sale_price']) ? $getProductfromCategoryData['sale_price'] : null ,
                                                    'quantity'=>$getProductfromCategoryData['quantity']
                                                  );
                                  
                                  }
                                 
                                    /* Get Products From Subcategory */
                                     $getcategorysProductDetails = ProductCategory::join('category', 'category.id', '=', 'productscategory.category_id')
                                                    ->join('products', 'products.id', '=', 'productscategory.product_id')  
                                                    ->where('productscategory.category_id', $request->category_id)
                                                    ->get()->toArray();
  
                             foreach($getcategorysDetails as $key=>$getcategoryvalue)
                                           { 
                                     $data['subcategory'][$key]=$getcategoryvalue;
                                     $getProductsDetails = ProductSubCategory::join('products', 'products.id', '=','productssubcategory.product_id')
                                                 ->where('productssubcategory.subcategory_id', $getcategoryvalue['subcategory_id'])
                                                 ->where('products.status',1)
                                                 ->limit(10)
                                                 ->get(['productssubcategory.subcategory_id','products.id','products.product_name','products.product_price','products.point','products.quantity','products.sale','products.sale_price'])->toArray();         
                        
                                     $getProductCount = ProductSubCategory::join('products', 'products.id', '=','productssubcategory.product_id')
                                                 ->where('productssubcategory.subcategory_id', $getcategoryvalue['subcategory_id'])
                                                 ->where('products.status',1)
                                                 ->count();  
                                         
                                         if($getProductCount){
                                          $getcategoryvalue['product_count'] = $getProductCount;                           
                                          }
                                 
                                     $data['subcategory'][$key]=$getcategoryvalue;   
                              foreach($getProductsDetails as $key3=>$getcategoryvalue3)
                              {
                                      $productImage = ProductsImage::select()->where('product_id',$getcategoryvalue3['id'])->get()->toArray();

                                      $data['subcategory'][$key]['product'][$key3]=array(
                                         'product_id' =>$getcategoryvalue3['id'],
                                         'product_image' =>$baseUrl['base_url'].$productImage[0]['product_image'],
                                         'product_name' =>$getcategoryvalue3['product_name'],
                                         'product_price' =>$getcategoryvalue3['product_price'],
                                         'point' =>$getcategoryvalue3['point'],
                                         'sale'=>$getcategoryvalue3['sale'],
                                         'sale_price'=>isset($getcategoryvalue3['sale_price']) ? $getcategoryvalue3['sale_price'] : null ,
                                         'quantity'=>$getcategoryvalue3['quantity']
                                        );     
                              }                        
                      }
                       
                      return response([
                            'category' => $data,
                            'message' => 'List of all category product'],
                             200);
                    }else{
                      
                      $validation = Validator::make($request->all(), [
                      'category_id' => 'required',
                      ]);
  
                  if($validation->fails()){
                      $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                      return $fieldsWithErrorMessagesArray;
                  } 
                }
           }   
           

           
           /*Get product from subcategory data*/
          public function productFromSubCategory(Request $request)
          {
                   $baseUrl= \Config::get('baseurl');
                   $data = array();
                           if(isset($request->subcategory_id))
                           {
                                    $getsubcategoryDetails= SubCategory::select()->where('subcategory.id',$request->subcategory_id)->first()->toArray();
                                    $data['subcategory_id'] = $getsubcategoryDetails['id'];
                                    $data['subcategory_name'] = $getsubcategoryDetails['sub_category_name'];
                         
                                    $getProductfromCategory = ProductSubCategory::select('products.*')
                                                  ->join('products', 'products.id', '=', 'productssubcategory.product_id')
                                                  ->where('productssubcategory.subcategory_id', $request->subcategory_id)
                                                  ->where('products.status',1)
                                                  ->get()
                                                  ->toArray();
             
                                    $getProductfromSubCategoryCount = ProductSubCategory::join('products', 'products.id', '=', 'productssubcategory.product_id')  
                                                  ->where('productssubcategory.subcategory_id', $request->subcategory_id)
                                                  ->where('products.status',1)
                                                  ->count();
                             
                                    $data['product_count']=$getProductfromSubCategoryCount;
                                      
                    foreach($getProductfromCategory as $getProductfromSubCategoryData){
                         
                                    $productImage = ProductsImage::select()->where('product_id',$getProductfromSubCategoryData['id'])->get()->toArray();
                                    $data['product'][] = array(
                                          'product_id' =>$getProductfromSubCategoryData['id'],
                                          'product_image' => $baseUrl['base_url'].$productImage[0]['product_image'],
                                          'product_name' =>$getProductfromSubCategoryData['product_name'],
                                          'product_price' =>$getProductfromSubCategoryData['product_price'],
                                          'point' =>$getProductfromSubCategoryData['point'],
                                          'sale'=>$getProductfromSubCategoryData['sale'],
                                          'sale_price'=>isset($getProductfromSubCategoryData['sale_price']) ? $getProductfromSubCategoryData['sale_price'] : null ,
                                          'quantity'=>$getProductfromSubCategoryData['quantity']
                                                );
                                              } 
                                            
                    return response([
                          'Subcategory' => $data,
                          'message' => 'List of all Subcategory product'],
                           200);
                            }
                    else{
                          $validation = Validator::make($request->all(), [
                          'subcategory_id' => 'required',
                              ]);
          
                    if($validation->fails()){
                              $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                              return $fieldsWithErrorMessagesArray;
                          } 
                        }
           }   
                   
   }
         
?>