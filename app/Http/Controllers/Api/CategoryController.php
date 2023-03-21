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
        $masterCategory = MasterCategory::all();
        
        if(count($masterCategory) == 0){
             return response([
                    'message' => 'Products are not found.',
                    'status' => 200], 200);
        }else{

            return response(['mastercategory' => $masterCategory, 
            'message' => 'Successful',
            'status' => 200], 200);
        }
    }
    public function mainCategory(Request $request)
    {

        if($request->mastercategory_id) {
            $getMainCategory = MasterMainCategory::select('mastermaincategory.*','mastercategory.id','maincategory.*')
            ->join('mastercategory', 'mastercategory.id', '=', 'mastermaincategory.mastercategory_id')
            ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
            ->where('mastermaincategory.mastercategory_id', $request->mastercategory_id)
            ->get();

           if(count($getMainCategory) == 0){
                return response([
                    'message' => 'Products are not found.',
                    'status' => 200], 200);
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
    }

    public function category(Request $request)
    {

        if($request->maincategory_id) {
            $getCategory = MainCategoryCategory::select('maincategorycategory.*','maincategory.id','category.*')
            ->join('maincategory', 'maincategory.id', '=', 'maincategorycategory.maincategory_id')
            ->join('category', 'category.id', '=', 'maincategorycategory.category_id')
            ->where('maincategorycategory.maincategory_id',$request->maincategory_id)
            ->get();

            if(count($getCategory) == 0){
                return response([
                    'message' => 'Products are not found.',
                    'status' => 200], 200);
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

                    //Return the validation error
                $fieldsWithErrorMessagesArray = $validation->messages()->get('*');
                return $fieldsWithErrorMessagesArray;
            } 
        }
    }
    public function subCategory(Request $request)
    {
        if($request->category_id) {
            $getSubCategory = CategorySubCategory::select('categorysubcategory.*','category.id','subcategory.*')
            ->join('category', 'category.id', '=', 'categorysubcategory.category_id')
            ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
            ->where('categorysubcategory.category_id',$request->category_id)
            ->get();

            if(count($getSubCategory) == 0){
                return response([
                    'message' => 'Products are not found.',
                    'status' => 200], 200);
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
    }

}


?>