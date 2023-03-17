<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterCategory;
use App\Models\MainCategory;
use App\Models\Category;
use App\Models\MasterMainCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(){

       return view('category.category');
   }
   public function create(){
        $data = MasterCategory::select('id','master_category_name')->get();
        return view('category.categoryadd',compact('data'));
    }
    public function store(Request $request){

        $this->validate($request, [
            'category_name' => 'required|string',
            'category_image' => 'required',
            'status' => 'required|in:1,2'
        ]);

          $path = public_path('categoryimage');

          if(!File::isDirectory($path)){
            File::makeDirectory($path, 0777, true, true);
            $imageName = time().'.'.$request->category_image->extension();  
            $request->category_image->move(public_path('categoryimage'), $imageName);
            $imagewithfolder = 'public\categoryimage\\'.$imageName;

        }else{
            $imageName = time().'.'.$request->category_image->extension();
            $request->category_image->move(public_path('categoryimage'), $imageName);
            $imagewithfolder = 'public\categoryimage\\'.$imageName;
        }

        $data = Category::create([
            'category_name' => $request->category_name,
            'category_image' => $imagewithfolder,
            'status' => $request->status
        ]);

        
        return redirect()->intended('category')->with('message','Data stored');

    }
   
}
