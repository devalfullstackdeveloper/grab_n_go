<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterCategory;
use Illuminate\Support\Facades\File;


class MasterCategoryController extends Controller
{
    public function index(){

        $data = MasterCategory::select()->orderBy('id','desc')->get();
        return view('mastercategory.mastercategory',compact('data'));

    }
    public function create(){

        return view('mastercategory.mastercategoryadd');
    }
    public function store(Request $request)
    {

        $this->validate($request, [
            'master_category_name' => 'required|string',
            'master_category_image' => 'required',
            'status' => 'required|in:1,2'
        ]);


        $path = public_path('mastercategoryimage');

        if(!File::isDirectory($path)){
            File::makeDirectory($path, 0777, true, true);
            $imageName = time().'.'.$request->master_category_image->extension();  
            $request->master_category_image->move(public_path('mastercategoryimage'), $imageName);
            
        }else{
            $imageName = time().'.'.$request->master_category_image->extension();
            $request->master_category_image->move(public_path('mastercategoryimage'), $imageName);
            $imagewithfolder = 'public\mastercategoryimage\\'.$imageName;
        }
        $data = MasterCategory::create([
            'master_category_name' => $request->master_category_name,
            'master_category_image' => $imagewithfolder,
            'status' => $request->status
        ]);
        return redirect()->intended('mastercategory')->with('message','Data stored');
    }

    public function edit($id){

        $data = MasterCategory::select()->where('id',$id)->first();
        return view('mastercategory.mastercategoryedit',compact('data'));
    }
    public function update(Request $request){

    // echo "<pre>";
    // print_r($request->all());
    // echo "</pre>";
    // exit();

        // $this->validate($request, [
        //     'master_category_name' => 'required|string',
        //     'master_category_image' => 'required',
        //     'status' => 'required|in:1,2'
        // ]);

        $path = public_path('mastercategoryimage');

        if($_FILES['master_category_image']['name'] != ''){
            if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);
                $imageName = time().'.'.$request->master_category_image->extension();  
                $request->master_category_image->move(public_path('mastercategoryimage'), $imageName);
                $imagewithfolder = 'public\mastercategoryimage\\'.$imageName;
                

            }else{
                $imageName = time().'.'.$request->master_category_image->extension();
                $request->master_category_image->move(public_path('mastercategoryimage'), $imageName);
                $imagewithfolder = 'public\mastercategoryimage\\'.$imageName;
            }

            $data = MasterCategory::where('id', $request->id)->update([
                'master_category_name' => isset($request->master_category_name) ? $request->master_category_name : '',
                'master_category_image' => isset($imagewithfolder) ? $imagewithfolder : '',
                'status' => isset($request->status) ? $request->status : ''
            ]);
        }else{
            $data = MasterCategory::where('id', $request->id)->update([
                'master_category_name' => isset($request->master_category_name) ? $request->master_category_name : '',
                'status' => isset($request->status) ? $request->status : ''
            ]);
        }




        return redirect()->intended('mastercategory')->with('message','Update the data');
        
    }
    public function show($id)
    {
        $data = MasterCategory::select()->where('id',$id)->first();
        return view('mastercategory.mastercategoryshow',compact('data'));
    }
    public function delete($id)
    {
        MasterCategory::find($id)->delete();

        return back();
    }
}
