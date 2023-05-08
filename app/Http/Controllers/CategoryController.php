<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategorySubCategory;
use App\Models\MainCategory;
use App\Models\MainCategoryCategory;
use App\Models\ProductAllCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index(){

        $getCategory = Category::select()->orderBy('id', 'desc')->get();

        $getMainCateName = array();

        foreach ($getCategory as $key => $value) {

            $getCateName = MainCategoryCategory::select('maincategorycategory.*','maincategory.*','category.*')
            ->join('maincategory', 'maincategory.id', '=', 'maincategorycategory.maincategory_id')
            ->join('category', 'category.id', '=', 'maincategorycategory.category_id')
            ->where('maincategorycategory.category_id',$value->id)
            ->where('category.status',1)
            ->where('category.isActive','1')
            ->get();

         if(count($getCateName) > 0){

            $data = array();

         foreach ($getCateName as $key => $value) {
            $data[] = $value->main_category_name;

        }
        $getName = implode(',', $data);

        $getMainCateName[] = array(
            "id" => $value->id,
            "main_category_title" => $getName,
            "category_title" => $value->category_name,
            "status" => $value->status,
            "isActive" => $value->isActive,
        ) ;          
    }
            
    }

        return view('category.category', compact('getMainCateName'));
    }

    public function create(){
        $data = MainCategory::select('id','main_category_name')->where('status',1)->get();
        return view('category.categoryadd',compact('data'));
    }
    public function store(Request $request){

        $this->validate($request, [
            'category_name' => 'required|string',
            'category_image' => 'required|mimes:jpeg,png,jpg',
            "maincategory_id" => "required|array|min:1",
            'status' => 'required|in:1,2'
        ]);

        $bannerpath = public_path('categorybannerimage');

        if ($_FILES['category_banner_image']['name'] && $_FILES['category_image']['name'] != '') {

            //category image

            $path = public_path('categoryimage');

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
                $imageName = time() . '.' . $request->category_image->extension();
                $request->category_image->move(public_path('categoryimage'), $imageName);
                $imagewithfolder = 'public/categoryimage/' . $imageName;

            } else {
                $imageName = time() . '.' . $request->category_image->extension();
                $request->category_image->move(public_path('categoryimage'), $imageName);
                $imagewithfolder = 'public/categoryimage/' . $imageName;
            }

            //cayegory banner image

            if (!File::isDirectory($bannerpath)) {
                File::makeDirectory($bannerpath, 0777, true, true);
                $imageName = time() . '.' . $request->category_banner_image->extension();
                $request->category_banner_image->move(public_path('categorybannerimage'), $imageName);
                $categorybannerimagewithfolder = 'public/categorybannerimage/' . $imageName;

            } else {
                $imageName = time() . '.' . $request->category_banner_image->extension();
                $request->category_banner_image->move(public_path('categorybannerimage'), $imageName);
                $categorybannerimagewithfolder = 'public/categorybannerimage/' . $imageName;
            }

            $data = Category::create([
                'category_name' => $request->category_name,
                'category_image' => $imagewithfolder,
                'category_banner_image' => $categorybannerimagewithfolder,
                'status' => $request->status,
            ]);

            foreach ($request->maincategory_id as $key => $value) {

                $storeCategorySubCategory = MainCategoryCategory::create([
                    'maincategory_id' => $value,
                    'category_id' => $data->id,
                ]);

            }
        } else {
            $path = public_path('categoryimage');

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
                $imageName = time() . '.' . $request->category_image->extension();
                $request->category_image->move(public_path('categoryimage'), $imageName);
                $imagewithfolder = 'public/categoryimage/' . $imageName;

            } else {
                $imageName = time() . '.' . $request->category_image->extension();
                $request->category_image->move(public_path('categoryimage'), $imageName);
                $imagewithfolder = 'public/categoryimage/' . $imageName;
            }
            $data = Category::create([
                'category_name' => $request->category_name,
                'category_image' => $imagewithfolder,
                'status' => $request->status,
            ]);

            foreach ($request->maincategory_id as $key => $value) {

                $storeCategorySubCategory = MainCategoryCategory::create([
                    'maincategory_id' => $value,
                    'category_id' => $data->id,
                ]);
            }
        }

        return redirect()->intended('category')->with('message', 'Data stored');

    }

    public function edit($id)
    {

        $categoryData = Category::select()->where('id', $id)->first();

        $data = MainCategoryCategory::select('maincategorycategory.*', 'maincategory.*', 'category.*')
            ->join('maincategory', 'maincategory.id', '=', 'maincategorycategory.maincategory_id')
            ->join('category', 'category.id', '=', 'maincategorycategory.category_id')
            ->where('maincategorycategory.category_id', $categoryData->id)
            ->where('maincategory.status', 1)
            ->get();

        $maincategory = array();
        $mastercategoryId = array();
        $getdata = array();

        foreach ($data as $key => $value) {
            $maincategory[$value->maincategory_id] = $value->main_category_name;
            $maincategoryId[] = $value->maincategory_id;
        }

        $getdata[] = array(
            "id" => $value->id,
            "main_category" => $maincategory,
            "main_category_id" => $maincategoryId,
            "category_name" => $value->category_name,
            "category_image" => $value->category_image,
            "category_banner_image" => $value->category_banner_image,
            "status" => $value->status,
        );

        $mainCategoryData = MainCategory::select('id', 'main_category_name')->where('status', 1)->get()->toArray();

        return view('category.categoryedit', compact('getdata', 'mainCategoryData'));
    }

    public function update(Request $request)
    {

        $this->validate($request, [
            'category_name' => 'required|string',
            "maincategory_id" => "required|array|min:1",
            'status' => 'required|in:1,2',
        ]);

        $path = public_path('categoryimage');

        if ($_FILES['category_image']['name'] != '') {
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
                $imageName = time() . '.' . $request->category_image->extension();
                $request->category_image->move(public_path('categoryimage'), $imageName);
                $imagewithfolder = 'public/categoryimage/' . $imageName;

            } else {
                $imageName = time() . '.' . $request->category_image->extension();
                $request->category_image->move(public_path('categoryimage'), $imageName);
                $imagewithfolder = 'public/categoryimage/' . $imageName;
            }
            $data = Category::where('id', $request->id)->update([
                'category_name' => isset($request->category_name) ? $request->category_name : '',
                'category_image' => isset($imagewithfolder) ? $imagewithfolder : '',
                'status' => isset($request->status) ? $request->status : '',
            ]);
        } else {
            $data = Category::where('id', $request->id)->update([
                'category_name' => isset($request->category_name) ? $request->category_name : '',
                'status' => isset($request->status) ? $request->status : '',
            ]);

        }

        $bannerpath = public_path('categorybannerimage');

        if ($_FILES['category_banner_image']['name'] != '') {
            if (!File::isDirectory($bannerpath)) {
                File::makeDirectory($bannerpath, 0777, true, true);
                $imageName = time() . '.' . $request->category_banner_image->extension();
                $request->category_banner_image->move(public_path('categorybannerimage'), $imageName);
                $bannerimagewithfolder = 'public/categorybannerimage/' . $imageName;

            } else {
                $imageName = time() . '.' . $request->category_banner_image->extension();
                $request->category_banner_image->move(public_path('categorybannerimage'), $imageName);
                $bannerimagewithfolder = 'public/categorybannerimage/' . $imageName;
            }
            $data = Category::where('id', $request->id)->update([
                'category_name' => isset($request->category_name) ? $request->category_name : '',
                'category_banner_image' => isset($bannerimagewithfolder) ? $bannerimagewithfolder : '',
                'status' => isset($request->status) ? $request->status : '',
            ]);
        } else {
            $data = Category::where('id', $request->id)->update([
                'category_name' => isset($request->category_name) ? $request->category_name : '',
                'status' => isset($request->status) ? $request->status : '',
            ]);

        }

        $deleteData = MainCategoryCategory::select()->where('category_id', $request->id)->delete();

        foreach ($request->maincategory_id as $key => $value) {
            $storeMasterMainCategory = MainCategoryCategory::create([
                'maincategory_id' => $value,
                'category_id' => $request->id,
            ]);

        }

        return redirect()->intended('category')->with('message', 'Update the data');
    }

    public function show($id)
    {
        $data = Category::select()->where('id', $id)->first();

        $getMainCategoryName = MainCategoryCategory::select('maincategorycategory.*', 'maincategory.*', 'category.*')
            ->join('maincategory', 'maincategory.id', '=', 'maincategorycategory.maincategory_id')
            ->join('category', 'category.id', '=', 'maincategorycategory.category_id')
            ->where('maincategorycategory.category_id', $data->id)
            ->get();

        $data = array();
        $getdata = array();
        foreach ($getMainCategoryName as $key => $value) {
            $data[] = $value->main_category_name;
        }

        $getName = implode(',', $data);

        $getdata[] = array(
            "id" => isset($value->id) ? $value->id : '',
            "main_category_name" => $getName,
            "category_name" => isset($value->category_name) ? $value->category_name : '',
            "category_image" => isset($value->category_image) ? $value->category_image : '',
            "category_banner_image" => $value->category_banner_image,
            "status" => isset($value->status) ? $value->status : '',
        );

        return view('category.categoryshow', compact('getdata'));
    }
    public function delete(Request $request)
    {
        $UpdateDetails4 = ProductAllCategory::select('products_all_category.*', 'products.id', 'category.*')
            ->distinct()
            ->join('category', 'category.id', '=', 'products_all_category.category_id')
            ->join('products', 'products.id', '=', 'products_all_category.product_id')
            ->where('category.id', $request->id)
            ->update([
                "products.isActive" => ('products' . $request->isActive == 1) ? 0 : 1,
            ]);

        $categoryDetails = CategorySubCategory::select('categorysubcategory.*', 'category.*', 'subcategory.*')
            ->join('category', 'category.id', '=', 'categorysubcategory.category_id')
            ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
            ->where('category.id', $request->id)
            ->get()
            ->toArray();

        foreach ($categoryDetails as $key => $categoryDetailsData) {
            $UpdateDetails3 = CategorySubCategory::select('categorysubcategory.*', 'category.*', 'subcategory.*')
                ->join('category', 'category.id', '=', 'categorysubcategory.category_id')
                ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
                ->where('category.id', $categoryDetailsData['category_id'])
                ->update([
                    "category.isActive" => ('category' . $request->isActive == 1) ? 0 : 1,
                    "subcategory.isActive" => ('subcategory' . $request->isActive == 1) ? 0 : 1,
                ]);
        }
        return back();
    }

}
