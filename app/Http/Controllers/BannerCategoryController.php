<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BannerCategory;
use App\Models\MasterCategory;
use App\Models\MainCategory;
use App\Models\Category;
use App\Models\SubCategory;
use DB;

class BannerCategoryController extends Controller
{
    public function index(){

        $getCategory = BannerCategory::select()->orderBy('id', 'desc')->get();

        $mastercategory = MasterCategory::select()->get();
        $maincategory = MainCategory::select()->get();
        $category = Category::select()->get();
        $subcategory = SubCategory::select()->get();

        $getProduct= BannerCategory::select('bannercategory.id','bannercategory.mastercategory_id','bannercategory.maincategory_id','bannercategory.category_id','bannercategory.subcategory_id','mastercategory.master_category_name','maincategory.main_category_name','category.category_name','subcategory.sub_category_name')
		->leftJoin('mastercategory', 'mastercategory.id', '=', 'bannercategory.mastercategory_id')
		->leftJoin('maincategory', 'maincategory.id', '=', 'bannercategory.maincategory_id')
		->leftJoin('category', 'category.id', '=', 'bannercategory.category_id')
		->leftJoin('subcategory', 'subcategory.id', '=', 'bannercategory.subcategory_id')
		->get(); 

        $data = array();
        foreach ($getProduct as $key => $value) 
        {
            $data[$key]['banner_id'] = $value->id;
            $data[$key]['mastercategory_id'] = $value->master_category_name;
            $data[$key]['maincategory_id'] = $value->main_category_name;
            $data[$key]['category_id'] = $value->category_name;
            $data[$key]['subcategory_id'] = $value->sub_category_name;
        }

        return view('bannercategory.bannercategory',compact('getCategory', 'data'));
    }
    public function create()
    {
        $tb1 = DB::table("mastercategory")->get();
        return view('bannercategory.bannercategorycreate', compact('tb1'));

    }

    //main-category dropdown filter
    public function maincategoryDropdownAjax($id)
    {
        $tb2 = DB::table("mastermaincategory")
            ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
            ->where("mastercategory_id", $id)->get();
        return json_encode($tb2);
    }

    //category dropdown filter
    public function categoryDropdownAjax($id)
    {
        $tb3 = DB::table("maincategorycategory")
            ->join('category','category.id', '=', 'maincategorycategory.category_id')
            ->where("maincategory_id", $id)->get();
        return json_encode($tb3);
    }

    //sub-category dropdown filter
    public function subCategoryDropdownAjax($id)
    {
        $tb4 = DB::table("categorysubcategory")
            ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
            ->where("category_id", $id)->get();
        return json_encode($tb4);
    }

    //to create bannercategory
    public function store(Request $request)
    {
        $this->validate($request, [
            'mastercategory_id' => 'required'
        ]);

        $storeExploreData = BannerCategory::create([
           'banner_id' => $request->id,
           'mastercategory_id' => $request->mastercategory_id,
           'maincategory_id' => isset($request->maincategory_id) ? $request->maincategory_id : 0,
           'category_id' => isset($request->category_id) ? $request->category_id : 0,
           'subcategory_id' => isset($request->subcategory_id) ? $request->subcategory_id : 0
        ]);

        $getProduct= BannerCategory::select('bannercategory.id','bannercategory.mastercategory_id','bannercategory.maincategory_id','bannercategory.category_id','bannercategory.subcategory_id','mastercategory.master_category_name','maincategory.main_category_name','category.category_name','subcategory.sub_category_name')
		->leftJoin('mastercategory', 'mastercategory.id', '=', 'bannercategory.mastercategory_id')
		->leftJoin('maincategory', 'maincategory.id', '=', 'bannercategory.maincategory_id')
		->leftJoin('category', 'category.id', '=', 'bannercategory.category_id')
		->leftJoin('subcategory', 'subcategory.id', '=', 'bannercategory.subcategory_id')
		->get(); 

        $data = array();
        foreach ($getProduct as $key => $value) 
        {
            $data[$key]['banner_id'] = $value->id;
            $data[$key]['mastercategory_id'] = $value->master_category_name;
            $data[$key]['maincategory_id'] = $value->main_category_name;
            $data[$key]['category_id'] = $value->category_name;
            $data[$key]['subcategory_id'] = $value->sub_category_name;
        }
        return redirect()->intended('bannercategory')->with('message','Data stored');        
    }
    //to delete bannercategory
    public function delete($id)
    {
        BannerCategory::find($id)->delete();
        return redirect()->back();
    }

}