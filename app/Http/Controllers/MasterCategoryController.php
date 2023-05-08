<?php

namespace App\Http\Controllers;

use App\Models\CategorySubCategory;
use App\Models\MainCategoryCategory;
use App\Models\MasterCategory;
use App\Models\MasterMainCategory;
use App\Models\ProductAllCategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MasterCategoryController extends Controller
{
    public function index(){

        $data = MasterCategory::select()->orderBy('id','desc')->where('isActive','1')->get();
        
        return view('mastercategory.mastercategory',compact('data'));

    }
    public function create(){

        return view('mastercategory.mastercategoryadd');
    }
    public function store(Request $request)
    {

        $this->validate($request, [
            'master_category_name' => 'required|string',
            'master_category_image' => 'required|mimes:jpeg,png,jpg',
            'status' => 'required|in:1,2'
        ]);


        $path = public_path('mastercategoryimage');

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
            $imageName = time() . '.' . $request->master_category_image->extension();
            $request->master_category_image->move(public_path('mastercategoryimage'), $imageName);
            $imagewithfolder = 'public/mastercategoryimage/' . $imageName;

        } else {
            $imageName = time() . '.' . $request->master_category_image->extension();
            $request->master_category_image->move(public_path('mastercategoryimage'), $imageName);
            $imagewithfolder = 'public/mastercategoryimage/' . $imageName;
        }
        $data = MasterCategory::create([
            'master_category_name' => $request->master_category_name,
            'master_category_image' => $imagewithfolder,
            'status' => $request->status,
        ]);
        return redirect()->intended('mastercategory')->with('message', 'Data stored');
    }

    public function edit($id)
    {

        $data = MasterCategory::select()->where('id', $id)->first();
        return view('mastercategory.mastercategoryedit', compact('data'));
    }
    public function update(Request $request)
    {

        $this->validate($request, [
            'master_category_name' => 'required|string',
            'status' => 'required|in:1,2',
        ]);

        $path = public_path('mastercategoryimage');

        if ($_FILES['master_category_image']['name'] != '') {
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
                $imageName = time() . '.' . $request->master_category_image->extension();
                $request->master_category_image->move(public_path('mastercategoryimage'), $imageName);
                $imagewithfolder = 'public/mastercategoryimage/' . $imageName;

            } else {
                $imageName = time() . '.' . $request->master_category_image->extension();
                $request->master_category_image->move(public_path('mastercategoryimage'), $imageName);
                $imagewithfolder = 'public/mastercategoryimage/' . $imageName;
            }

            $data = MasterCategory::where('id', $request->id)->update([
                'master_category_name' => isset($request->master_category_name) ? $request->master_category_name : '',
                'master_category_image' => isset($imagewithfolder) ? $imagewithfolder : '',
                'status' => isset($request->status) ? $request->status : '',
            ]);
        } else {
            $data = MasterCategory::where('id', $request->id)->update([
                'master_category_name' => isset($request->master_category_name) ? $request->master_category_name : '',
                'status' => isset($request->status) ? $request->status : '',
            ]);
        }

        return redirect()->intended('mastercategory')->with('message', 'Update the data');

    }
    public function show($id)
    {
        $data = MasterCategory::select()->where('id', $id)->first();
        return view('mastercategory.mastercategoryshow', compact('data'));
    }
    public function delete(Request $request)
    {
        $UpdateDetails4 = ProductAllCategory::select('products_all_category.*','products.id', 'mastercategory.*')
        ->distinct()
        ->join('mastercategory', 'mastercategory.id', '=', 'products_all_category.mastercategory_id')
        ->join('products', 'products.id', '=', 'products_all_category.product_id')
        ->where('mastercategory.id', $request->id)
        ->update([
            "products.isActive" => ('products'.$request->isActive == 1) ? 0 : 1,
        ]);

        $UpdateDetails = MasterMainCategory::select('mastermaincategory.*', 'mastercategory.*', 'maincategory.*')
            ->join('mastercategory', 'mastercategory.id', '=', 'mastermaincategory.mastercategory_id')
            ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
            ->where('mastercategory.id', $request->id)
            ->update([
                "mastercategory.isActive" => ('mastercategory' . $request->isActive == 1) ? 0 : 1,
                "maincategory.isActive" => ('maincategory' . $request->isActive == 1) ? 0 : 1,
            ]);
        
        $mainCategoryDetails = MasterMainCategory::select('mastermaincategory.*', 'mastercategory.*', 'maincategory.*')
            ->join('mastercategory', 'mastercategory.id', '=', 'mastermaincategory.mastercategory_id')
            ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
            ->where('mastercategory.id', $request->id)
            ->get()
            ->toArray();

        foreach ($mainCategoryDetails as $key => $mainCategoryDetailsData) {

            $UpdateDetails2 = MainCategoryCategory::select('maincategorycategory.*', 'maincategory.*', 'category.*')
                ->join('maincategory', 'maincategory.id', '=', 'maincategorycategory.maincategory_id')
                ->join('category', 'category.id', '=', 'maincategorycategory.category_id')
                ->where('maincategory.id', $mainCategoryDetailsData['maincategory_id'])
                ->update([
                    "category.isActive" => ('category' . $request->isActive == 1) ? 0 : 1,
                ]);

            $categoryDetails = MainCategoryCategory::select('maincategorycategory.*', 'maincategory.*', 'category.*')
                ->join('maincategory', 'maincategory.id', '=', 'maincategorycategory.maincategory_id')
                ->join('category', 'category.id', '=', 'maincategorycategory.category_id')
                ->where('maincategory.id', $mainCategoryDetailsData['maincategory_id'])
                ->get()
                ->toArray();

            foreach ($categoryDetails as $key => $categoryDetailsData) {

                $UpdateDetails3 = CategorySubCategory::select('categorysubcategory.*', 'category.*', 'subcategory.*')
                    ->join('category', 'category.id', '=', 'categorysubcategory.category_id')
                    ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
                    ->where('category.id', $categoryDetailsData['category_id'])
                    ->update([
                        "subcategory.isActive" => ('subcategory' . $request->isActive == 1) ? 0 : 1,
                    ]);

            }
        }
       
        return back();
    }
}
