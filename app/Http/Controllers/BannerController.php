<?php

namespace App\Http\Controllers;

use App\Models\Banner;
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
		return view('banner.bannercreate');
	}

	public function store(Request $request)
	{
    $this->validate($request, [
      'banner_name' => 'required|string',
      'banner_image' => 'required|mimes:jpeg,png,jpg',
      'banner_offer_type' => 'required'
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
      'banner_offer_type' => $request->banner_offer_type
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
    $banner_data= Banner::find($id);
    return view('banner.banneredit',compact('banner_data'));
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
     ));

      }else{
       $UpdateDetails = Banner::where('id', $request->id)->update(array(
       "banner_name" => $request->banner_name,
       "banner_offer_type" => $request->banner_offer_type,
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
