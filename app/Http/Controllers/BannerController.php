<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\MasterCategory;
use App\Models\MainCategory;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\MasterMainCategory;
use App\Models\MainCategoryCategory;
use App\Models\CategorySubCategory;
use Illuminate\Http\Request;
use File;

class BannerController extends Controller
{
	public function index()
	{
		$banners = Banner::select()->orderBy('id','desc')->get();
		return view('banner.banner',compact('banners'));
	
	}

  	public function create()
  	{
      $masterCategory = MasterCategory::select()->where('status','1')->get();
      return view('banner.banneradd',compact('masterCategory'));
    }


    //main-category dropdown filter
    public function mainCategoryDropdown($id)
    {

      $mainCategory = MasterMainCategory::select('mastermaincategory.*','maincategory.*')
      ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
      ->where("mastercategory_id", $id)
      ->where('maincategory.status',1)
      ->get();


      return json_encode($mainCategory);
    }

    //category dropdown filter
    public function categoryDropdown($id)
    {
      $category = MainCategoryCategory::select('maincategorycategory.*','category.*')
      ->join('category','category.id', '=', 'maincategorycategory.category_id')
      ->where("maincategory_id", $id)
      ->where('category.status',1)
      ->get();
      return json_encode($category);
    }

    //sub-category dropdown filter
    public function subCategoryDropdown($id)
    {
      $subCategory = CategorySubCategory::select('categorysubcategory.*','subcategory.*')
      ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
      ->where("category_id", $id)
      ->where('subcategory.status',1)
      ->get();
      return json_encode($subCategory);
    }


    public function store(Request $request)
    {

      $this->validate($request, [
        'banner_name' => 'required|string',
        'banner_image' => 'required|mimes:jpeg,png,jpg',
        'banner_offer_type' => 'required',
        'status' => 'required',
        'mastercategory_id' => 'required'
      ]);

      $path = public_path('banner_image');

      if(!File::isDirectory($path)){
        File::makeDirectory($path, 0777, true, true);
        $imageName = time().'.'.$request->banner_image->extension();  
        $request->banner_image->move(public_path('banner_image'), $imageName);
        $imagewithfolder = 'public\banner_image\\'.$imageName;

      }else{
        $imageName = time().'.'.$request->banner_image->extension();
        $request->banner_image->move(public_path('banner_image'), $imageName);
        $imagewithfolder = 'public\banner_image\\'.$imageName;
      }
      $data = Banner::create([
        'banner_name' => $request->banner_name,
        'banner_image' => $imagewithfolder,
        'banner_offer_type' => $request->banner_offer_type,
        'status' => $request->status,
        'mastercategory_id' => $request->mastercategory_id,
        'maincategory_id' => isset($request->maincategory_id) ? $request->maincategory_id : '0' ,
        'category_id' => isset($request->category_id) ? $request->category_id : '0',
        'subcategory_id' => isset($request->subcategory_id) ? $request->subcategory_id :'0',
      ]);
      return redirect()->intended('banner')->with('message','Data stored');
    }

    public function show( $id)
    {
      $bannerData = Banner::select()->where('id',$id)->first()->toArray();
      $masterCategoryData = MasterCategory::select()->where('id',$bannerData['mastercategory_id'])->get()->toArray();
      $mainCategoryData = MainCategory::select()->where('id',$bannerData['maincategory_id'])->get()->toArray();
      $categoryData = Category::select()->where('id',$bannerData['category_id'])->get()->toArray();
      $subCategoryData = SubCategory::select()->where('id',$bannerData['subcategory_id'])->get()->toArray();
      return view('banner.bannershow',compact('bannerData','masterCategoryData','mainCategoryData','categoryData','subCategoryData'));
    }

    public function edit( $id)
    {

      //master category data
      $bannerData = Banner::select('banners.*','mastercategory.master_category_name')
      ->join('mastercategory','mastercategory.id','=','banners.mastercategory_id')
      ->where('banners.id',$id)
      ->get()->toArray();

      //main category data
      $bannerMainCategoryData = MasterMainCategory::select('mastermaincategory.*','maincategory.main_category_name')
      ->join('maincategory','maincategory.id','=','mastermaincategory.maincategory_id')
      ->where('mastercategory_id',$bannerData[0]['mastercategory_id'])
       ->where('maincategory.status',1)
       ->get()->toArray();

      //category data
      $bannerCategoryData = MainCategoryCategory::select('maincategorycategory.*','category.category_name')
      ->join('category','category.id','=','maincategorycategory.category_id')
      ->where('maincategory_id',$bannerData[0]['maincategory_id'])
      ->where('category.status',1)
      ->get()->toArray();

      //sub category data
      $bannerSubCategoryData = CategorySubCategory::select('categorysubcategory.*','subcategory.sub_category_name')
      ->join('subcategory','subcategory.id','=','categorysubcategory.subcategory_id')
      ->where('category_id',$bannerData[0]['category_id'])
      ->where('subcategory.status',1)
      ->get()->toArray();

      $masterCategoryId[] = $bannerData[0]['mastercategory_id'];
      $mainCategoryId[] = $bannerData[0]['maincategory_id'];
      $categoryId[] = $bannerData[0]['category_id'];
      $subCategoryId[] = $bannerData[0]['subcategory_id'];

      $bannerMasterData[] = array(
        "id" => $bannerData[0]['id'],
        "banner_name" => $bannerData[0]['banner_name'],
        "banner_image" => $bannerData[0]['banner_image'],
        "banner_offer_type" => $bannerData[0]['banner_offer_type'],
        "status" => $bannerData[0]['status'],
        "mastercategory_id" => $masterCategoryId,
        "maincategory_id" => $mainCategoryId,
        "category_id" => $categoryId,
        "subcategory_id" => $subCategoryId,
      );
       
      $masterCategoryData =  MasterCategory::select('id','master_category_name')->where('status',1)->get()->toArray();

      return view('banner.banneredit',compact('bannerMasterData','masterCategoryData','bannerMainCategoryData','bannerCategoryData','bannerSubCategoryData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    { 

    
      $request->validate([
        'banner_name' => 'required',
        'banner_offer_type' => 'required',
        'status' => 'required',
        'mastercategory_id' => 'required'
      ]);

      if($_FILES['banner_image']['name'] != '')
      {
            //upload image
        $path = public_path('banner_image');

        if(!File::isDirectory($path)){
          File::makeDirectory($path, 0777, true, true);
          $imageName = time().'.'.$request->banner_image->extension();  
          $request->banner_image->move(public_path('banner_image'), $imageName);
          $imagewithfolder = 'public\banner_image\\'.$imageName;

        }else{
          $imageName = time().'.'.$request->banner_image->extension();
          $request->banner_image->move(public_path('banner_image'), $imageName);
          $imagewithfolder = 'public\banner_image\\'.$imageName;
        }

        $UpdateDetails = Banner::where('id', $request->id)->update(array(
         "banner_name" => $request->banner_name,
         "banner_image" => $imagewithfolder,
         "banner_offer_type" => $request->banner_offer_type,
         "status" => $request->status,
         "mastercategory_id" => $request->mastercategory_id,
         "maincategory_id" => isset($request->maincategory_id) ? $request->maincategory_id : '0' ,
         "category_id" => isset($request->category_id) ? $request->category_id : '0',
         "subcategory_id" => isset($request->subcategory_id) ? $request->subcategory_id :'0',
       ));

      }else{
       $UpdateDetails = Banner::where('id', $request->id)->update(array(
        "banner_name" => $request->banner_name,
        "banner_offer_type" => $request->banner_offer_type,
        "status" => $request->status,
        "mastercategory_id" => $request->mastercategory_id,
        "maincategory_id" => isset($request->maincategory_id) ? $request->maincategory_id : '0',
        "category_id" => isset($request->category_id) ? $request->category_id : '0',
        "subcategory_id" => isset($request->subcategory_id) ? $request->subcategory_id :'0',
      ));

     }
     return redirect()->route('banner.list')
     ->with('message','Data updated');
   }
      /**
     * Remove the specified resource from storage.
     */
      public function delete($id)
      {

        Banner::find($id)->delete();
        return back();

      }
    }
