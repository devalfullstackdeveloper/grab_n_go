<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MainCategoryCategory;
use App\Models\CategorySubCategory;
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\MasterMainCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SubCategoryController extends Controller
{
    public function index(){

        $getSubCategory = SubCategory::select()->orderBy('id','desc')->get();

        $getCategoryName = array();

        foreach ($getSubCategory as $key => $value) {

            $getCateName = CategorySubCategory::select('categorysubcategory.*', 'category.*', 'subcategory.*')
                ->join('category', 'category.id', '=', 'categorysubcategory.category_id')
                ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
                ->where('categorysubcategory.subcategory_id', $value->id)
                ->get();

            $data = array();

            foreach ($getCateName as $key => $value) {
                $data[] = $value->category_name;
            }

            $getName = implode(',', $data);

            $getCategoryName[] = array(
                "id" => $value->id,
                "category_title" => $getName,
                "sub_category_title" => $value->sub_category_name,
                "status" => $value->status,
            );

        }

        return view('subcategory.subcategory', compact('getCategoryName'));
    }

    public function create()
    {
        $data = Category::select('id', 'category_name')->where('status', 1)->get();
        return view('subcategory.subcategoryadd', compact('data'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'sub_category_name' => 'required|string',
            'sub_category_image' => 'required|mimes:jpeg,png,jpg',
            "category_id" => "required|array|min:1",
            'status' => 'required|in:1,2',
        ]);

        $path = public_path('subcategoryimage');

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
            $imageName = time() . '.' . $request->sub_category_image->extension();
            $request->sub_category_image->move(public_path('subcategoryimage'), $imageName);
            $imagewithfolder = 'public/subcategoryimage/' . $imageName;

        } else {
            $imageName = time() . '.' . $request->sub_category_image->extension();
            $request->sub_category_image->move(public_path('subcategoryimage'), $imageName);
            $imagewithfolder = 'public/subcategoryimage/' . $imageName;
        }

        $data = SubCategory::create([
            'sub_category_name' => $request->sub_category_name,
            'sub_category_image' => $imagewithfolder,
            'status' => $request->status,
        ]);

        foreach ($request->category_id as $key => $value) {

            $storeMasterMainCategory = CategorySubCategory::create([
                'category_id' => $value,
                'subcategory_id' => $data->id,
            ]);

        }

        return redirect()->intended('subcategory')->with('message', 'Data stored');

    }

    public function edit($id)
    {

        $subCategoryData = SubCategory::select()->where('id', $id)->first();

        $data = CategorySubCategory::select('categorysubcategory.*', 'category.*', 'subcategory.*')
            ->join('category', 'category.id', '=', 'categorysubcategory.category_id')
            ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
            ->where('categorysubcategory.subcategory_id', $subCategoryData->id)
            ->where('category.status', 1)
            ->get();

        $category = array();
        $categoryId = array();
        $getdata = array();

        foreach ($data as $key => $value) {
            $category[$value->category_id] = $value->category_name;
            $categoryId[] = $value->category_id;
        }

        $getdata[] = array(
            "id" => $value->id,
            "category" => $category,
            "category_id" => $categoryId,
            "sub_category_name" => $value->sub_category_name,
            "sub_category_image" => $value->sub_category_image,
            "status" => $value->status,
        );

        $categoryData = Category::select('id', 'category_name')->where('status', 1)->get()->toArray();

        return view('subcategory.subcategoryedit', compact('getdata', 'categoryData'));
    }

    public function update(Request $request)
    {

        $this->validate($request, [
            'sub_category_name' => 'required|string',
            "category_id" => "required|array|min:1",
            'status' => 'required|in:1,2',
        ]);
        $path = public_path('subcategoryimage');

        if ($_FILES['sub_category_image']['name'] != '') {
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
                $imageName = time() . '.' . $request->sub_category_image->extension();
                $request->sub_category_image->move(public_path('subcategoryimage'), $imageName);
                $imagewithfolder = 'public/subcategoryimage/' . $imageName;

            } else {
                $imageName = time() . '.' . $request->sub_category_image->extension();
                $request->sub_category_image->move(public_path('subcategoryimage'), $imageName);
                $imagewithfolder = 'public/subcategoryimage/' . $imageName;
            }
            $data = SubCategory::where('id', $request->id)->update([
                'sub_category_name' => isset($request->sub_category_name) ? $request->sub_category_name : '',
                'sub_category_image' => isset($imagewithfolder) ? $imagewithfolder : '',
                'status' => isset($request->status) ? $request->status : '',
            ]);
        } else {
            $data = SubCategory::where('id', $request->id)->update([
                'sub_category_name' => isset($request->sub_category_name) ? $request->sub_category_name : '',
                'status' => isset($request->status) ? $request->status : '',
            ]);

        }

        $deleteData = CategorySubCategory::select()->where('subcategory_id', $request->id)->delete();

        foreach ($request->category_id as $key => $value) {
            $storeMasterMainCategory = CategorySubCategory::create([
                'category_id' => $value,
                'subcategory_id' => $request->id,
            ]);

        }

        return redirect()->intended('subcategory')->with('message', 'Update the data');
    }

    public function show($id)
    {
        $data = SubCategory::select()->where('id', $id)->first();

        $getCategoryName = CategorySubCategory::select('categorysubcategory.*', 'category.*', 'subcategory.*')
            ->join('category', 'category.id', '=', 'categorysubcategory.category_id')
            ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
            ->where('categorysubcategory.subcategory_id', $data->id)
            ->get();

        $data = array();
        $getdata = array();
        foreach ($getCategoryName as $key => $value) {
            $data[] = $value->category_name;
        }

        $getName = implode(',', $data);

        $getdata[] = array(
            "id" => isset($value->id) ? $value->id : '',
            "category_name" => $getName,
            "sub_category_name" => isset($value->sub_category_name) ? $value->sub_category_name : '',
            "sub_category_image" => isset($value->sub_category_image) ? $value->sub_category_image : '',
            "status" => isset($value->status) ? $value->status : '',
        );

        return view('subcategory.subcategoryshow', compact('getdata'));
    }
    public function delete($id)
    {

        SubCategory::find($id)->delete();
        return back();
    }

}
