<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterCategory;
use App\Models\MainCategory;
use App\Models\MasterMainCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MainCategoryController extends Controller
{
    public function index(){

        $getMainCategory = MainCategory::select()->orderBy('id','desc')->get();

       return view('maincategory.maincategory',compact('getMainCategory'));
   }
   public function create(){
        $data = MasterCategory::select('id','master_category_name')->get();
        return view('maincategory.maincategoryadd',compact('data'));
    }

    public function store(Request $request){

        $this->validate($request, [
            'main_category_name' => 'required|string',
            'main_category_image' => 'required',
                // 'mastercategory_id.*' => 'required|in',
            'status' => 'required|in:1,2'
        ]);

          $path = public_path('maincategoryimage');

          if(!File::isDirectory($path)){
            File::makeDirectory($path, 0777, true, true);
            $imageName = time().'.'.$request->main_category_image->extension();  
            $request->main_category_image->move(public_path('maincategoryimage'), $imageName);
            $imagewithfolder = 'public\maincategoryimage\\'.$imageName;

        }else{
            $imageName = time().'.'.$request->main_category_image->extension();
            $request->main_category_image->move(public_path('maincategoryimage'), $imageName);
            $imagewithfolder = 'public\maincategoryimage\\'.$imageName;
        }
        $data = MainCategory::create([
            'main_category_name' => $request->main_category_name,
            'main_category_image' => $imagewithfolder,
            'status' => $request->status
        ]);

        foreach ($request->mastercategory_id as $key => $value) {
         $storeMasterMainCategory = MasterMainCategory::create([
            'mastercategory_id'=>$value,
            'maincategory_id'=> $data->id
        ]);

        }
        return redirect()->intended('maincategory')->with('message','Data stored');

    }

    public function edit($id){

        $mainCategoryData = MainCategory::select()->where('id',$id)->first();

        $masterCategoryData = MasterCategory::select('id','master_category_name')->get();

        return view('maincategory.maincategoryedit',compact('mainCategoryData','masterCategoryData'));
    }

     public function show($id)
    {
        $data = MainCategory::select()->where('id',$id)->first();
        return view('maincategory.maincategoryshow',compact('data'));
    }

    public function delete($id)
    {
        MainCategory::find($id)->delete();

        return back();
    }

}
