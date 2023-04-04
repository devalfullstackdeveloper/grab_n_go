<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Explore;
use App\Models\ExploreExploreCategory;
use App\Models\MainCategory;
use App\Models\MasterCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use DB;

class ExploreController extends Controller
{
    public function index()
    {
        $getProduct = Explore::select()->orderBy('id', 'desc')->get();
        $data = array();
        foreach ($getProduct as $key => $value) {
            $data[$key]['explore_id'] = $value->id;
            $data[$key]['explore_name'] = $value->explore_name;
            $data[$key]['explore_details'] = $value->explore_details;
            $data[$key]['mastercategory_id'] = $value->mastercategory_id;
            $data[$key]['maincategory_id'] = $value->maincategory_id;
            $data[$key]['category_id'] = $value->category_id;
            $data[$key]['subcategory_id'] = $value->subcategory_id;
        }
        return view('explore.explore', compact('data'));
    }

    //master-category dropdown filter
    public function create()
    {
        $tb1 = DB::table("mastercategory")->pluck('master_category_name', 'id');
        return view('explore.explorecreate', compact('tb1'));
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
    //to create data in explore table
    public function store(Request $request)
    {
        $this->validate($request, [
            'explore_name' => 'required|unique:explore',
            'explore_details' => 'required',
        ]);

        $storeExploreData = Explore::create([
            'explore_name' => $request->explore_name,
            'explore_details' => $request->explore_details,
        ]);
       return redirect()->route('explore.exploreedit',['id'=>$storeExploreData->id]);
    }

    //to fetch explore product details and explore product all catagory details
    public function edit(Request $request ,$explore_id)
    {
        $mastercategory = MasterCategory::select()->get();
        $maincategory = MainCategory::select()->get();
        $category = Category::select()->get();
        $subcategory = SubCategory::select()->get();

        $explore_data = Explore::select()->where('id',$explore_id)->first();

        $getExploreData = array();
        $getExploreData[] = array(
            'explore_id' => $explore_data->id,
            'explore_name' => $explore_data->explore_name,
            'explore_details' => $explore_data->explore_details,
        );
 
        $getProduct= ExploreExploreCategory::select('exploreexplorecategory.id','exploreexplorecategory.explore_id','exploreexplorecategory.mastercategory_id','exploreexplorecategory.maincategory_id','exploreexplorecategory.category_id','exploreexplorecategory.subcategory_id','mastercategory.master_category_name','maincategory.main_category_name','category.category_name','subcategory.sub_category_name')
		->leftJoin('mastercategory', 'mastercategory.id', '=', 'exploreexplorecategory.mastercategory_id')
		->leftJoin('maincategory', 'maincategory.id', '=', 'exploreexplorecategory.maincategory_id')
		->leftJoin('category', 'category.id', '=', 'exploreexplorecategory.category_id')
		->leftJoin('subcategory', 'subcategory.id', '=', 'exploreexplorecategory.subcategory_id')
		->where('exploreexplorecategory.explore_id',$request->id)
		->get();

        $data = array();
        foreach ($getProduct as $key => $value) 
        {
            $data[$key]['explore_id'] = $value->id;
            $data[$key]['mastercategory_id'] = $value->master_category_name;
            $data[$key]['maincategory_id'] = $value->main_category_name;
            $data[$key]['category_id'] = $value->category_name;
            $data[$key]['subcategory_id'] = $value->sub_category_name;
        }
        return view('explore.exploreedit', compact('data','getExploreData', 'mastercategory', 'maincategory', 'category', 'subcategory'));

    }

    //to update explore product details and create explore product catagory details
    public function update(Request $request)
    {
        $UpdateDetails = Explore::where('id', $request->id)->update(array(
                'explore_name' => $request->explore_name,
                'explore_details' => $request->explore_details,
            )); 

        return redirect()->route('explore.exploreedit',['id'=>$request->id])->with('message','Data updated');
    }

    public function exploreCategoryStore(Request $request)
    {
        $this->validate($request, [
            'mastercategory_id' => 'required'
        ]);
        $storeExploreData = ExploreExploreCategory::create([
            'explore_id' => $request->id,
            'mastercategory_id' => $request->mastercategory_id,
            'maincategory_id' => isset($request->maincategory_id) ? $request->maincategory_id : 0,
           'category_id' => isset($request->category_id) ? $request->category_id : 0,
           'subcategory_id' => isset($request->subcategory_id) ? $request->subcategory_id : 0
        ]);

        return redirect()->route('explore.exploreedit',['id'=>$request->id])->with('message','Data updated');
    }
    
    //to delete explore product details
    public function delete($id)
    {
        Explore::find($id)->delete();
        ExploreExploreCategory::select()->where('explore_id',$id)->delete();
        return back();
    }

    //to delete explore product all catecory detail for one record
    public function exploreCategoryDelete($id)
    {
        ExploreExploreCategory::find($id)->delete();
        return redirect()->back();
    }

}