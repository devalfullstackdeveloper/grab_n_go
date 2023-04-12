<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\MasterCategory;
use App\Models\MainCategory;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
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
    $masterCategory = MasterCategory::select()->get();
    return view('banner.banneradd',compact('masterCategory'));
  }


    //main-category dropdown filter
  public function mainCategoryDropdownAjax($id)
  {
    $mainCategory = DB::table("mastermaincategory")
    ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
    ->where("mastercategory_id", $id)->get();
    return json_encode($mainCategory);
  }

    //category dropdown filter
  public function categoryDropdownAjax($id)
  {
    $category = DB::table("maincategorycategory")
    ->join('category','category.id', '=', 'maincategorycategory.category_id')
    ->where("maincategory_id", $id)->get();
    return json_encode($category);
  }

    //sub-category dropdown filter
  public function subCategoryDropdownAjax($id)
  {
    $subCategory = DB::table("categorysubcategory")
    ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
    ->where("category_id", $id)->get();
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
    $banner_data= Banner::find($id);
    return view('banner.bannershow',compact('banner_data'));
  }

  public function edit( $id)
  {

    

    $bannerData= Banner::where('id',$id)->get()->toArray();

    
    return view('banner.banneredit',compact('bannerData'));
  }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    { 

    
      $request->validate([
        'banner_name' => 'required',
        'banner_offer_type' => 'required'
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
       ));

      }else{
       $UpdateDetails = Banner::where('id', $request->id)->update(array(
         "banner_name" => $request->banner_name,
         "banner_offer_type" => $request->banner_offer_type,
         "status" => $request->status,
         
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
