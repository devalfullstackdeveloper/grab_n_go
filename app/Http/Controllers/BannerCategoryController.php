<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BannerCategory;
use App\Models\MasterCategory;
use App\Models\MainCategory;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\MasterMainCategory;
use App\Models\MainCategoryCategory;
use App\Models\CategorySubCategory;

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
        $masterCategoryData = MasterCategory::select()->get();
        return view('bannercategory.bannercategoryadd', compact('masterCategoryData'));

    }

    //main-category dropdown filter
    public function maincategoryDropdownAjax($id)
    {
        
        $mainCategoryData = MasterMainCategory::select('mastermaincategory.*','maincategory.*')
            ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
            ->where("mastercategory_id", $id)->get();
        return json_encode($mainCategoryData);
    }

    //category dropdown filter
    public function categoryDropdownAjax($id)
    {
        $categoryData = MainCategoryCategory::select('maincategorycategory.*','category.*')
            ->join('category','category.id', '=', 'maincategorycategory.category_id')
            ->where("maincategory_id", $id)->get();
        return json_encode($categoryData);
    }

    //sub-category dropdown filter
    public function subCategoryDropdownAjax($id)
    {
        $subCategoryData = CategorySubCategory::select('categorysubcategory.*','subcategory.*')
            ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
            ->where("category_id", $id)->get();
        return json_encode($subCategoryData);
    }

    //to create bannercategory
    public function store(Request $request)
    {
        $this->validate($request, [
            'mastercategory_id' => 'required'
        ]);

        $input = $request->all();

        $data_level = '';

        $mastercategory_id = $input['mastercategory_id'];
        $maincategory_id = 0;
        $category_id = 0;
        $subcategory_id = 0;

        if(isset($input['mastercategory_id'])  && $input['mastercategory_id'] != ''  && $input['maincategory_id'] == 0 )
        {


            $data_level = 'master';
            $banner_data = BannerCategory::where('mastercategory_id','=',$input['mastercategory_id'])->where('maincategory_id','=','0')->get();
            if(count($banner_data->toArray()) > 0)
            {
                return redirect()->intended('bannercategory/add')->with('message','Duplicate Data');  
            }
        }
      
        if(isset($input['maincategory_id'])  && $input['maincategory_id'] != 0  && $input['category_id'] == 0)
        {


            $data_level = 'main';
           
            $banner_data = BannerCategory::where('mastercategory_id','=',$input['mastercategory_id'])
                            ->where('maincategory_id','=',$input['maincategory_id'])
                            ->where('category_id','=','0')->get();
                           
            if(count($banner_data->toArray()) == 0)
            {
                $maincategory_id = $input['maincategory_id'];
            }
            else
            {
                return redirect()->intended('bannercategory/add')->with('message','Duplicate Data');
            }
        }

        if(isset($input['category_id'])  && $input['category_id'] != 0 && $input['subcategory_id'] == 0)
        {
            $data_level = 'category';
            $banner_data = BannerCategory::where('mastercategory_id','=',$input['mastercategory_id'])
                            ->where('maincategory_id','=',$input['maincategory_id'])
                            ->where('category_id','=',$input['category_id'])
                            ->where('subcategory_id','=','0')->get();

            if(count($banner_data->toArray()) == 0)
            {
                $maincategory_id = $input['maincategory_id'];
                $category_id = $input['category_id'];
            }
            else
            {
                return redirect()->intended('bannercategory/add')->with('message','Duplicate Data');
            }                           
            
        }

        if(isset($input['subcategory_id'])  && $input['subcategory_id'] != 0 )
        {
            $data_level = 'category';
            $banner_data = BannerCategory::where('mastercategory_id','=',$input['mastercategory_id'])
                            ->where('maincategory_id','=',$input['maincategory_id'])
                            ->where('category_id','=',$input['category_id'])
                            ->where('subcategory_id','=',$input['subcategory_id'])->get();

            if(count($banner_data->toArray()) == 0)
            {
                $maincategory_id = $input['maincategory_id'];
                $category_id = $input['category_id'];
                $subcategory_id = $input['subcategory_id'];
            }
            else
            {
                return redirect()->intended('bannercategory/add')->with('message','Duplicate Data');
            }                            
        }

       

        $storeExploreData = BannerCategory::create([
        'mastercategory_id' => $mastercategory_id,
        'maincategory_id' =>  $maincategory_id,
        'category_id' =>  $category_id,
        'subcategory_id' =>  $subcategory_id
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