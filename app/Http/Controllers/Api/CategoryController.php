<?php

namespace App\Http\Controllers\Api;

use App\Models\MasterCategory;
use App\Models\MasterMainCategory;
use App\Models\MainCategory;
use App\Models\MainCategoryCategory;
use App\Models\CategorySubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function masterCategory()
    {     
        $masterCategory = MasterCategory::select()->where('status',1)->get();
        
        if(count($masterCategory) == 0){
           return response([
            'message' => 'Category are not found.'], 200);
       }else{
            return response(['mastercategory' => $masterCategory, 
            'message' => 'Successful',
            'status' => 200], 200);
        }
    }

    public function mainCategory(Request $request)
    {

        if($request->mastercategory_id != '0'){

            if($request->mastercategory_id) {

                $getMainCategory = MasterMainCategory::select('mastermaincategory.*','mastercategory.id','maincategory.*')
                    ->join('mastercategory', 'mastercategory.id', '=', 'mastermaincategory.mastercategory_id')
                    ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
                    ->where('mastermaincategory.mastercategory_id', $request->mastercategory_id)
                    ->where('maincategory.status', 1)
                    ->get();

                    if(count($getMainCategory) == 0){
                        return response([
                            'message' => 'Category are not found.'], 200);
                    }else{
                        return response(['maincategory' => $getMainCategory,
                            'message' => 'Successful',
                            'status' => 200], 200);
                    }
            }else{  

                $validation = Validator::make($request->all(), [
                    'mastercategory_id' => 'required',
                ]);

                if($validation->fails()){
                    $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                    return $fieldsWithErrorMessagesArray;
                } 
            }

        }else{
           return response([
            'message' => 'Category are not found.'], 200);
        }

    }

    public function category(Request $request)
    {

        if($request->maincategory_id != '0'){

            if($request->maincategory_id) {
                $getCategory = MainCategoryCategory::select('maincategorycategory.*','maincategory.id','category.*')
                    ->join('maincategory', 'maincategory.id', '=', 'maincategorycategory.maincategory_id')
                    ->join('category', 'category.id', '=', 'maincategorycategory.category_id')
                    ->where('maincategorycategory.maincategory_id',$request->maincategory_id)
                    ->where('category.status', 1)
                    ->get();

                if(count($getCategory) == 0){
                    return response([
                        'message' => 'Category are not found.'], 200);
                }else{
                    return response(['category' => $getCategory,
                        'message' => 'Successful',
                        'status' => 200], 200);
                }
            }else{
                $validation = Validator::make($request->all(), [
                    'maincategory_id' => 'required',
                ]);

                if($validation->fails()){
                    $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                    return $fieldsWithErrorMessagesArray;
                } 
            }
        }else{
           return response([
            'message' => 'Category are not found.'], 200); 
        }
    }

    public function subCategory(Request $request)
    {
        if($request->category_id != '0') {
            if($request->category_id) {
                $getSubCategory = CategorySubCategory::select('categorysubcategory.*','category.id','subcategory.*')
                    ->join('category', 'category.id', '=', 'categorysubcategory.category_id')
                    ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
                    ->where('categorysubcategory.category_id',$request->category_id)
                    ->where('subcategory.status', 1)
                    ->get();

                if(count($getSubCategory) == 0){
                    return response([
                        'message' => 'Category are not found.'], 200);
                }else{
                    return response(['subcategory' => $getSubCategory,
                        'message' => 'Successful',
                        'status' => 200], 200);
                }
            }else{
                $validation = Validator::make($request->all(), [
                    'category_id' => 'required',
                ]);
                if($validation->fails()){
                    $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                    return $fieldsWithErrorMessagesArray;
                }
            }
        }else{ 
           return response([
            'message' => 'Category are not found.'], 200); 
       }
    }

        public function masterMainCategory()
        {

            $masterCategory = MasterCategory::all();
            $baseUrl= \Config::get('baseurl');

            $masterCategoryData = array();
            
            foreach ($masterCategory as $key => $value) {
                
                $getMainCategory = MasterMainCategory::select('mastermaincategory.mastercategory_id','mastercategory.master_category_name','maincategory.*','maincategory.main_category_name','maincategory.main_category_image')
                ->join('mastercategory', 'mastercategory.id', '=', 'mastermaincategory.mastercategory_id')
                ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
                ->where('mastermaincategory.mastercategory_id', $value->id)
                ->get()
                ->toArray();

                $mainCategoryData = array();
                  
                foreach ($getMainCategory as $getMainCategoryData) {
                    $mainCategoryData[] = array(
                        "maincategory_id" =>  $getMainCategoryData['id'],
                        "maincategory_name" =>  $getMainCategoryData['main_category_name'],
                        "main_category_image" =>  $baseUrl['base_url'].$getMainCategoryData['main_category_image'],
                    );                        
                }

               if(count($mainCategoryData) != 0){
                $masterCategoryData[] = array(
                    "master_category_name" => $value->master_category_name,
                    "master_category_id" => $value->id,
                    "main_category" => $mainCategoryData,
                );
                }
               
             }
                   
                return response(['master_main_category' => $masterCategoryData,
                'message' => 'Successful',
                'status' => 200], 200);
        }

}

?>