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
        $masterCategory = MasterCategory::select()->where('status', 1)->where('mastercategory.isActive', '1')->get()->toArray();

        $baseUrl = \Config::get('baseurl');
        $masterCategoryData = array();
        foreach ($masterCategory as $key => $value) {
            $master_category_image = str_replace('\\', '/', $value['master_category_image']);
            $masterCategoryData[] = array(
                'mastercategory_id' => $value['id'],
                'master_category_name' => $value['master_category_name'],
                'master_category_image' => $baseUrl['base_url'] . $master_category_image,
            );

        }

        if (count($masterCategory) == 0) {
            return response([
                'message' => 'Products are not found.'], 200);
        } else {
            return response(['mastercategory' => $masterCategoryData,
                'message' => 'Successful',
                'status' => 200], 200);
        }
    }

    public function mainCategory(Request $request)
    {

        if ($request->mastercategory_id != '0') {

            if ($request->mastercategory_id) {

                $getMainCategory = MasterMainCategory::select('mastermaincategory.*', 'mastercategory.id', 'maincategory.*')
                    ->join('mastercategory', 'mastercategory.id', '=', 'mastermaincategory.mastercategory_id')
                    ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
                    ->where('mastermaincategory.mastercategory_id', $request->mastercategory_id)
                    ->where('maincategory.isActive', '1')
                    ->get();

                $baseUrl = \Config::get('baseurl');
                $mainCategoryData = array();
                foreach ($getMainCategory as $key => $value) {
                    $main_category_image = str_replace('\\', '/', $value['main_category_image']);
                    $mainCategoryData[] = array(
                        'maincategory_id' => $value['id'],
                        'main_category_name' => $value['main_category_name'],
                        'main_category_image' => $baseUrl['base_url'] . $main_category_image,
                    );

                }

                if (count($getMainCategory) == 0) {
                    return response([
                        'message' => 'Products are not found.'], 200);
                } else {
                    return response(['maincategory' => $mainCategoryData,
                        'message' => 'Successful',
                        'status' => 200], 200);
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

        } else {
            return response([
                'message' => 'Products are not found.'], 200);
        }

    }

    public function category(Request $request)
    {

        if ($request->maincategory_id != '0') {

            if ($request->maincategory_id) {
                $getCategory = MainCategoryCategory::select('maincategorycategory.*', 'maincategory.id', 'category.*')
                    ->join('maincategory', 'maincategory.id', '=', 'maincategorycategory.maincategory_id')
                    ->join('category', 'category.id', '=', 'maincategorycategory.category_id')
                    ->where('maincategorycategory.maincategory_id', $request->maincategory_id)
                    ->where('category.isActive', '1')
                    ->get();

                $baseUrl = \Config::get('baseurl');
                $categoryData = array();
                foreach ($getCategory as $key => $value) {
                    $category_image = str_replace('\\', '/', $value['category_image']);
                    $categoryData[] = array(
                        'category_id' => $value['id'],
                        'category_name' => $value['category_name'],
                        'category_image' => $baseUrl['base_url'] . $category_image,
                    );

                }

                if (count($getCategory) == 0) {
                    return response([
                        'message' => 'Products are not found.'], 200);
                } else {
                    return response(['category' => $categoryData,
                        'message' => 'Successful',
                        'status' => 200], 200);
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
        } else {
            return response([
                'message' => 'Products are not found.'], 200);
        }
    }

    public function subCategory(Request $request)
    {
        if ($request->category_id != '0') {
            if ($request->category_id) {
                $getSubCategory = CategorySubCategory::select('categorysubcategory.*', 'category.id', 'subcategory.*')
                    ->join('category', 'category.id', '=', 'categorysubcategory.category_id')
                    ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
                    ->where('categorysubcategory.category_id', $request->category_id)
                    ->where('subcategory.isActive', '1')
                    ->get();

                $baseUrl = \Config::get('baseurl');
                $subCategoryData = array();
                foreach ($getSubCategory as $key => $value) {
                    $sub_category_image = str_replace('\\', '/', $value['sub_category_image']);
                    $subCategoryData[] = array(
                        'sub_category_id' => $value['id'],
                        'sub_category_name' => $value['sub_category_name'],
                        'sub_category_image' => $baseUrl['base_url'] . $sub_category_image,
                    );

                }

                if (count($getSubCategory) == 0) {
                    return response([
                        'message' => 'Products are not found.'], 200);
                } else {
                    return response(['subcategory' => $subCategoryData,
                        'message' => 'Successful',
                        'status' => 200], 200);
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
        } else {
            return response([
                'message' => 'Products are not found.'], 200);
        }
    }

    public function masterMainCategory()
    {

        $masterCategory = MasterCategory::all();
        $baseUrl = \Config::get('baseurl');

        $masterCategoryData = array();

        foreach ($masterCategory as $key => $value) {

            $getMainCategory = MasterMainCategory::select('mastermaincategory.mastercategory_id', 'mastercategory.master_category_name', 'maincategory.*', 'maincategory.main_category_name', 'maincategory.main_category_image')->distinct()
                ->join('mastercategory', 'mastercategory.id', '=', 'mastermaincategory.mastercategory_id')
                ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
                ->join('products_all_category', 'products_all_category.maincategory_id', '=', 'mastermaincategory.maincategory_id')
                ->where('mastermaincategory.mastercategory_id', $value->id)
                ->get()
                ->toArray();

            $mainCategoryData = array();

            foreach ($getMainCategory as $getMainCategoryData) {
                $mainCategoryData[] = array(
                    "master_category_id" => $value->id,
                    "maincategory_id" => $getMainCategoryData['id'],
                    "maincategory_name" => $getMainCategoryData['main_category_name'],
                    "main_category_image" => $baseUrl['base_url'] . $getMainCategoryData['main_category_image'],
                );
            }

            if (count($mainCategoryData) != 0) {
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