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

        $getMasterCateName = array();

        foreach ($getMainCategory as $key => $value) {

         $getMasterCategoryName = MasterMainCategory::select('mastermaincategory.*','mastercategory.*','maincategory.*')
         ->join('mastercategory', 'mastercategory.id', '=', 'mastermaincategory.mastercategory_id')
         ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
         ->where('mastermaincategory.maincategory_id',$value->id)
         ->get();

         $data = array();

         foreach ($getMasterCategoryName as $key => $value) {
            $data[] = $value->master_category_name;
        }

        $getName = implode(',', $data);

        $getMasterCateName[] = array(
            "id" => $value->id,
            "master_category_title" => $getName,
            "main_category_title" => $value->main_category_name,
            "status" => $value->status,
        ) ;    
    }

    return view('maincategory.maincategory',compact('getMasterCateName'));

}

public function create(){
    $data = MasterCategory::select('id','master_category_name')->get();
    return view('maincategory.maincategoryadd',compact('data'));
}

public function store(Request $request){

    $this->validate($request, [
        "main_category_name" => 'required|string',
        "main_category_image" =>  'required|mimes:jpeg,png,jpg',
        "mastercategory_id" => "required|array|min:1",
        "status" => 'required|in:1,2'
    ]);
        $bannerpath = public_path('maincategorybannerimage');

        if($_FILES['main_category_banner_image']['name'] && $_FILES['main_category_image']['name']   != ''){

        //main category image
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
        //main category banner image
        if(!File::isDirectory($bannerpath)){
        File::makeDirectory($bannerpath, 0777, true, true);
        $imageName = time().'.'.$request->main_category_banner_image->extension();  
        $request->main_category_banner_image->move(public_path('maincategorybannerimage'), $imageName);
        $maincategorybannerimagewithfolder = 'public\maincategorybannerimage\\'.$imageName;

        }else{
            $imageName = time().'.'.$request->main_category_banner_image->extension();
            $request->main_category_banner_image->move(public_path('maincategorybannerimage'), $imageName);
            $maincategorybannerimagewithfolder = 'public\maincategorybannerimage\\'.$imageName;
        }
        $data = MainCategory::create([
            'main_category_name' => $request->main_category_name,
            'main_category_image' => $imagewithfolder,
            'main_category_banner_image' => $maincategorybannerimagewithfolder,
            'status' => $request->status
        ]);

        foreach ($request->mastercategory_id as $key => $value) {
            $storeMasterMainCategory = MasterMainCategory::create([
                'mastercategory_id'=>$value,
                'maincategory_id'=> $data->id
            ]);
        }
    }else
    {
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
        }
        return redirect()->intended('maincategory')->with('message','Data stored');

    }

    public function edit($id){

    $mainCategoryData = MainCategory::select()->where('id',$id)->first();

    $data= MasterMainCategory::select('mastermaincategory.mastercategory_id','mastercategory.master_category_name','maincategory.status','maincategory.id','maincategory.main_category_name','maincategory.main_category_image')
    ->join('mastercategory', 'mastercategory.id', '=', 'mastermaincategory.mastercategory_id')
    ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
    ->where('mastermaincategory.maincategory_id',$mainCategoryData->id)
    ->get();

    $mastercategory = array();
    $mastercategoryId = array();
    $getdata = array();

    foreach ($data as $key => $value) {
        $mastercategory[$value->mastercategory_id] = $value->master_category_name;
        $mastercategoryId[] = $value->mastercategory_id;
    }


    $getdata[] = array(
        "id" => isset($value->id) ? $value->id : '',
        "master_category" => $mastercategory,
        "master_category_id" => $mastercategoryId,
        "main_category_name" => isset($value->main_category_name) ? $value->main_category_name : '',
        "main_category_image" => isset($value->main_category_image) ? $value->main_category_image : '',
        "main_category_banner_image" => isset($value->main_category_banner_image) ? $value->main_category_banner_image : '',
        "status" => isset($value->status) ? $value->status : '',
    ) ;

    $masterCategoryData = MasterCategory::select('id','master_category_name')->get()->toArray();

    return view('maincategory.maincategoryedit',compact('getdata','masterCategoryData'));
}

public function update(Request $request){

    $this->validate($request, [
        "main_category_name" => 'required|string',
        "mastercategory_id" => "required|array|min:1",
        "status" => 'required|in:1,2'
    ]);

    $bannerpath = public_path('maincategorybannerimage');
    $path = public_path('maincategoryimage');

    if($_FILES['main_category_banner_image']['name'] != ''){
        if(!File::isDirectory($bannerpath)){
            File::makeDirectory($bannerpath, 0777, true, true);
            $imageName = time().'.'.$request->main_category_banner_image->extension();  
            $request->main_category_banner_image->move(public_path('maincategorybannerimage'), $imageName);
            $maincategorybannerimagewithfolder = 'public\maincategorybannerimage\\'.$imageName;

        }else{
            $imageName = time().'.'.$request->main_category_banner_image->extension();
            $request->main_category_banner_image->move(public_path('maincategorybannerimage'), $imageName);
            $maincategorybannerimagewithfolder = 'public\maincategorybannerimage\\'.$imageName;
        }
        $data = MainCategory::where('id', $request->id)->update([
            'main_category_name' => isset($request->main_category_name) ? $request->main_category_name : '',
            'main_category_banner_image' => isset($maincategorybannerimagewithfolder) ? $maincategorybannerimagewithfolder : '',
            'status' => isset($request->status) ? $request->status : ''
        ]);
    }else{
        $data = MainCategory::where('id', $request->id)->update([
            'main_category_name' => isset($request->main_category_name) ? $request->main_category_name : '',
            'status' => isset($request->status) ? $request->status : ''
        ]);

    }
    if($_FILES['main_category_image']['name'] != ''){
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
        $data = MainCategory::where('id', $request->id)->update([
            'main_category_name' => isset($request->main_category_name) ? $request->main_category_name : '',
            'main_category_image' => isset($imagewithfolder) ? $imagewithfolder : '',
            'status' => isset($request->status) ? $request->status : ''
        ]);
    }else{
        $data = MainCategory::where('id', $request->id)->update([
            'main_category_name' => isset($request->main_category_name) ? $request->main_category_name : '',
            'status' => isset($request->status) ? $request->status : ''
        ]);

    }



    $deleteData = MasterMainCategory::select()->where('maincategory_id',$request->id)->delete();

    foreach ($request->mastercategory_id as $key => $value) {
        $storeMasterMainCategory = MasterMainCategory::create([
            'mastercategory_id'=>$value,
            'maincategory_id'=> $request->id
        ]);

    }

    return redirect()->intended('maincategory')->with('message','Update the data');
} 


public function show($id)
{
    $data = MainCategory::select()->where('id',$id)->first();

    $getMasterCategoryName = MasterMainCategory::select('mastermaincategory.*','mastercategory.*','maincategory.*')
    ->join('mastercategory', 'mastercategory.id', '=', 'mastermaincategory.mastercategory_id')
    ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
    ->where('mastermaincategory.maincategory_id',$data->id)
    ->get();

    $data = array();
    $getdata = array();
    foreach ($getMasterCategoryName as $key => $value) {
        $data[] = $value->master_category_name;
    }

    $getName = implode(',', $data);


    $getdata[] = array(
        "id" => $value->id,
        "master_category_name" => $getName,
        "main_category_name" => $value->main_category_name,
        "main_category_image" => $value->main_category_image,
        "main_category_banner_image" => $value->main_category_banner_image,
        "status" => $value->status,

    ) ;

    return view('maincategory.maincategoryshow',compact('getdata'));    
}

public function delete($id)
{

    MainCategory::find($id)->delete();
    return back();
}


}
