<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\MasterCategory;
use App\Models\MainCategory;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductMasterCategory;
use App\Models\ProductMainCategory;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\ProductsImage;
use App\Models\ProductLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;




class ProductController extends Controller
{
 public function index(){
    $getProduct = Product::select()->orderBy('id','desc')->get();

    $data = array();
    foreach ($getProduct as $key => $value) {

        $data[$key]['product_id'] = $value->id;
        $data[$key]['product_name'] = $value->product_name;
        $data[$key]['product_details'] = $value->product_details;
        $data[$key]['product_price'] = $value->product_price;
        $data[$key]['quantity'] = $value->quantity;
        $data[$key]['point'] = $value->point;
        $data[$key]['sale'] = $value->sale;
        $data[$key]['sale_price'] = $value->sale_price;
        $data[$key]['status'] = $value->status;

        $getProductMasterCatName = array();
        $getProductMasterCategory = ProductMasterCategory::select()
        ->join('mastercategory','mastercategory.id' ,'=' ,'productsmastercategory.mastercategory_id')
        ->where('product_id' , $value->id)->get();

        foreach ($getProductMasterCategory as $k => $value1) {
            $getProductMasterCatName[$k] = $value1->master_category_name;
        }
        $data[$key]['master_category_name'] = implode(',', $getProductMasterCatName);

        $getProductMainCatName = array();
        $getProductMainCategory = ProductMainCategory::select()
        ->join('maincategory','maincategory.id' ,'=' ,'productsmaincategory.maincategory_id')
        ->where('product_id' , $value->id)->get();

        foreach ($getProductMainCategory as $k1 => $value2) {
            $getProductMainCatName[$k1] = $value2->main_category_name;
        }
        $data[$key]['main_category_name'] = implode(',', $getProductMainCatName);

        $getProductCatName = array();
        $getProductCategory = ProductCategory::select()
        ->join('category','category.id' ,'=' ,'productscategory.category_id')
        ->where('product_id' , $value->id)->get();

        foreach ($getProductCategory as $k2 => $value3) {
            $getProductCatName[$k2] = $value3->category_name;
        }
        $data[$key]['category_name'] = implode(',', $getProductCatName);  

        $getProductSubCatName = array();
        $getProductSubCategory = ProductSubCategory::select()
        ->join('subcategory','subcategory.id' ,'=' ,'productssubcategory.subcategory_id')
        ->where('product_id' , $value->id)->get();

        foreach ($getProductSubCategory as $k3 => $value4) {
            $getProductSubCatName[$k3] = $value4->sub_category_name;
        }
        $data[$key]['sub_category_name'] = implode(',', $getProductSubCatName); 

        $getProductLatName = array();
        $getProductLongName = array();
        $getProductLatLong = ProductLocation::select()
        ->where('product_id' , $value->id)->get();

        foreach ($getProductLatLong as $k7 => $value8) {
            $getProductLatName[$k7] = $value8->lat;
            $getProductLongName[$k7] = $value8->long;
        }  
        $data[$key]['lat'] = implode(',', $getProductLatName);   
        $data[$key]['long'] = implode(',', $getProductLongName);   
    }
    return view('product.product',compact('data'));

}
public function create()
{

 $masterCategorydata = MasterCategory::select('id','master_category_name')->where('status',1)->get();
 $mainCategorydata = MainCategory::select('id','main_category_name')->where('status',1)->get();
 $categorydata = Category::select('id','category_name')->where('status',1)->get();
 $subCategorydata = SubCategory::select('id','sub_category_name')->where('status',1)->get();

 return view('product.productadd',compact('masterCategorydata','mainCategorydata','categorydata','subCategorydata'));

}
public function store(Request $request){
  
    $this->validate($request, [
        "product_name" => 'required|string',
        'product_image' => 'required',
        'product_image.*' => 'mimes:jpg,png,jpeg,svg',
        "product_details" => 'required|min:15',
        "product_price" => 'required',
        "mastercategory_id" => "required|array|min:1",
        "maincategory_id" => "required|array|min:1",
        "status" => 'required|in:1,2',
        "quantity" => 'required',
        "product_price" => 'required',
        "sale" => 'required',
        "point" => 'required',
        "lat.*" => 'required',
        "long.*" => 'required',
    ]);

    $data = Product::create([
    'product_name' => $request->product_name,
    // 'product_image' => $imagewithfolder,
    'product_details' => $request->product_details,
    'product_price' => $request->product_price,
    'quantity' => $request->quantity,
    'product_price' => $request->product_price,
    'sale' => $request->sale,
    'point' => $request->point,
    'sale_price' => $request->sale_price,
    'status' => $request->status,
    
    ]);

    $path = public_path('productimage');

    foreach($request->product_image as $prodimage)
    {
            
      if(!File::isDirectory($path)){
        File::makeDirectory($path, 0777, true, true);
        $imageName = time().rand(1,50).'.'.$prodimage->extension();  
        $prodimage->move(public_path('productimage'), $imageName);
        $imagewithfolder = 'public\productimage\\'.$imageName;
            // $prodimage = array();

    }else{
        $imageName = time().rand(1,50).'.'.$prodimage->extension();
        $prodimage->move(public_path('productimage'), $imageName);
        $imagewithfolder = 'public\productimage\\'.$imageName;
            // $prodimage = array();

    }

    $imagedata = ProductsImage::create([
        'product_image' => $imagewithfolder,
        'product_id'=> $data->id
    ]);
}   

foreach ($request->mastercategory_id as $key => $value) {
   $storeProductMasterCategory = ProductMasterCategory::create([
    'mastercategory_id'=>$value,
    'product_id'=> $data->id
]);

}
foreach ($request->maincategory_id as $key => $value) {
   $storeProductMainCategory = ProductMainCategory::create([
    'maincategory_id'=>$value,
    'product_id'=> $data->id
]);

}
if(isset($request->category_id)){
foreach ($request->category_id as $key => $value) {
   $storeProductCategory = ProductCategory::create([
    'category_id'=>$value,
    'product_id'=> $data->id
]);

}
}
if(isset($request->subcategory_id)){
foreach ($request->subcategory_id as $key => $value) {
   $storeProductSubCategory = ProductSubCategory::create([
    'subcategory_id'=>$value,
    'product_id'=> $data->id
]);

}
}

foreach ($request->lat as $key => $value) {
   $storeProductLocatin = ProductLocation::create([
    'lat'=>$value,
    'long' => $request->long[$key],
    'product_id'=> $data->id
    ]);

}


return redirect()->intended('product')->with('message','Data stored');

}

public function show(Request $request,$id)
{ 
      $getProduct = Product::select()->where('id',$id)->first();

        $data = array();

        $data['product_name'] = $getProduct->product_name;
        $data['product_details'] = $getProduct->product_details;
        $data['product_price'] = $getProduct->product_price;
        $data['quantity'] = $getProduct->quantity;
        $data['point'] = $getProduct->point;
        $data['sale'] = $getProduct->sale;
        $data['sale_price'] = $getProduct->sale_price;
        $data['status'] = $getProduct->status;

        $getProductMasterCatName = array();
        $getProductMasterCategory = ProductMasterCategory::select()
        ->join('mastercategory','mastercategory.id' ,'=' ,'productsmastercategory.mastercategory_id')
        ->where('product_id' ,$id)->get();

        foreach ($getProductMasterCategory as $k => $value1) {
            $getProductMasterCatName[$k] = $value1->master_category_name;
        }
        $data['master_category_name'] = implode(',', $getProductMasterCatName);

        $getProductMainCatName = array();
        $getProductMainCategory = ProductMainCategory::select()
        ->join('maincategory','maincategory.id' ,'=' ,'productsmaincategory.maincategory_id')
        ->where('product_id' , $id)->get();

        foreach ($getProductMainCategory as $k1 => $value2) {
            $getProductMainCatName[$k1] = $value2->main_category_name;
        }
        $data['main_category_name'] = implode(',', $getProductMainCatName);

        $getProductCatName = array();
        $getProductCategory = ProductCategory::select()
        ->join('category','category.id' ,'=' ,'productscategory.category_id')
        ->where('product_id' , $id)->get();

        foreach ($getProductCategory as $k2 => $value3) {
            $getProductCatName[$k2] = $value3->category_name;
        }
        $data['category_name'] = implode(',', $getProductCatName);  

        $getProductSubCatName = array();
        $getProductSubCategory = ProductSubCategory::select()
        ->join('subcategory','subcategory.id' ,'=' ,'productssubcategory.subcategory_id')
        ->where('product_id' , $id)->get();

        foreach ($getProductSubCategory as $k3 => $value4) {
            $getProductSubCatName[$k3] = $value4->sub_category_name;
        }
        $data['sub_category_name'] = implode(',', $getProductSubCatName); 

        $getProductImageName = array();
        $getProductImage= ProductsImage::select()
        ->where('product_id' , $id)->get();
       
        foreach ($getProductImage as $k4 => $value5) {
            
            $getProductImageName[$k4] = $value5->product_image;
        }
        $data['product_image'] = $getProductImageName;

        $getProductLatName = array();
        $getProductLongName = array();
        $getProductLatLong = ProductLocation::select()
        ->where('product_id' , $id)->get();

        foreach ($getProductLatLong as $k7 => $value8) {
            $getProductLatName[$k7] = $value8->lat;
            $getProductLongName[$k7] = $value8->long;
        }  
        $data['lat'] = implode(',', $getProductLatName);   
        $data['long'] = implode(',', $getProductLongName);   
      
    return view('product.productshow',compact('data'));    
}


public function edit($id){

    $getProduct = Product::select()->where('id',$id)->first();

        $data = array();

        $data['product_id'] = $getProduct->id;
        $data['product_name'] = $getProduct->product_name;
        $data['product_details'] = $getProduct->product_details;
        $data['product_price'] = $getProduct->product_price;
        $data['quantity'] = $getProduct->quantity;
        $data['point'] = $getProduct->point;
        $data['sale'] = $getProduct->sale;
        $data['sale_price'] = $getProduct->sale_price;
        $data['status'] = $getProduct->status;

        $getProductMasterCatName = array();
        $mastercategoryId = array();
        $getProductMasterCategory = ProductMasterCategory::select()
        ->join('mastercategory','mastercategory.id' ,'=' ,'productsmastercategory.mastercategory_id')
        ->where('product_id' ,$id)->get();

        foreach ($getProductMasterCategory as $k => $value1) {
            $getProductMasterCatName[$value1->mastercategory_id] = $value1->master_category_name;
            $mastercategoryId[] = $value1->mastercategory_id;
        }
        
         $data["master_category_name"] = $getProductMasterCatName; 
         $data["master_category_id"] = $mastercategoryId; 
       
        $masterCategoryData = MasterCategory::select('id','master_category_name')->where('status',1)->get()->toArray();


        $getProductMainCatName = array();
        $maincategoryId = array();
        $getProductMainCategory = ProductMainCategory::select()
        ->join('maincategory','maincategory.id' ,'=' ,'productsmaincategory.maincategory_id')
        ->where('product_id' ,$id)->get();

        foreach ($getProductMainCategory as $k => $value2) {
            $getProductMainCatName[$value2->maincategory_id] = $value2->main_category_name;
            $maincategoryId[] = $value2->maincategory_id;
        }
        
         $data["main_category_name"] = $getProductMainCatName; 
         $data["main_category_id"] = $maincategoryId;
       
        $mainCategoryData = MainCategory::select('id','main_category_name')->where('status',1)->get()->toArray();

        $getProductCatName = array();
        $categoryId = array();
        $getProductCategory = ProductCategory::select()
        ->join('category','category.id' ,'=' ,'productscategory.category_id')
        ->where('product_id' ,$id)->get();

        foreach ($getProductCategory as $k => $value3) {
            $getProductCatName[$value3->category_id] = $value3->category_name;
            $categoryId[] = $value3->category_id;
        }
        
         $data["category_name"] = $getProductCatName; 
         $data["category_id"] = $categoryId;
       
        $categoryData = Category::select('id','category_name')->where('status',1)->get()->toArray();

        $getProductSubCatName = array();
        $subcategoryId = array();
        $getProductSubCategory = ProductSubCategory::select()
        ->join('subcategory','subcategory.id' ,'=' ,'productssubcategory.subcategory_id')
        ->where('product_id' ,$id)->get();

        foreach ($getProductSubCategory as $k => $value4) {
            $getProductSubCatName[$value4->subcategory_id] = $value4->sub_category_name;
            $subcategoryId[] = $value4->subcategory_id;
        }
        
         $data["sub_category_name"] = $getProductSubCatName; 
         $data["subcategory_id"] = $subcategoryId;
       
        $subCategoryData = SubCategory::select('id','sub_category_name')->where('status',1)->get()->toArray();

        $getProductLatName = array();
        $getProductLongName = array();
        $getProductLatLongName = array();

        $getProductLatLong = ProductLocation::select()
        ->where('product_id' , $id)->get();


        foreach ($getProductLatLong as $k7 => $value8) {
            if($value8->lat != ''){

                $getProductLatName[$k7] = $value8->lat;
                $getProductLongName[$k7] = $value8->long;

                $getProductLatLongName[$k7]= "<div class='entry input-group'>
                                            <input type='text' class='form-control' name='lat[]' value='".$value8->lat."'>".
                                            "<input type='text' class='form-control' name='long[]' value='".$value8->long."'>".
                                            "<span class='input-group-btn'>
                                            <button class='btn btn-remove btn-default' type='button'><i class='fa fa-minus' aria-hidden='true'></i></button>
                                            </span>
                                            </div>";

            }
            
        } 

        $data['lat'] = $getProductLatName;   
        $data['long'] = $getProductLongName;
        $data['latlong'] = $getProductLatLongName;


        $LatLongData = ProductLocation::select('id','lat','long')->get()->toArray();   

        return view('product.productedit',compact('data','masterCategoryData','mainCategoryData','categoryData','subCategoryData'));    
    }


public function update(Request $request){


    $this->validate($request, [
        "product_name" => 'required|string',
        "product_details" => 'required|string',
        "product_price" => 'required',
        "mastercategory_id" => "required|array|min:1",
        "maincategory_id" => "required|array|min:1",
        "status" => 'required|in:1,2',
        "quantity" => 'required',
        "product_price" => 'required',
        "sale" => 'required',
        "point" => 'required',
        "lat" => "required|array|min:1",
        "long" => "required|array|min:1",
    ]);

   $UpdateDetails = Product::where('id', $request->id)->update([
       "product_name" => isset($request->product_name) ? $request->product_name : '',
       "product_details" => isset($request->product_details) ? $request->product_details : '',
       "product_price" =>isset($request->product_price) ? $request->product_price : '',
       "status" =>isset($request->status) ? $request->status : '',
       "quantity" => isset($request->quantity) ? $request->quantity : '',
       "sale" => isset($request->sale) ? $request->sale : '',
       "sale_price" => isset($request->sale_price) ? $request->sale_price : NULL,
       "point" =>isset($request->point) ? $request->point : ''
     ]);
  

    $path = public_path('productimage');
    
 if(isset($request->product_image)){
   $deleteImageData = ProductsImage::select()->where('product_id',$request->id)->delete();
   
    foreach($request->product_image as $prodimage)
    {

    if($_FILES['product_image']['name'] != ''){
         if(!File::isDirectory($path)){
        File::makeDirectory($path, 0777, true, true);
        $imageName = time().rand(1,50).'.'.$prodimage->extension();  
        $prodimage->move(public_path('productimage'), $imageName);
        $imagewithfolder = 'public\productimage\\'.$imageName;

    }else{
        $imageName = time().rand(1,50).'.'.$prodimage->extension();
        $prodimage->move(public_path('productimage'), $imageName);
        $imagewithfolder = 'public\productimage\\'.$imageName;
            // $prodimage = array();
    }

    $storeImage = ProductsImage::create([
            'product_image' => isset($imagewithfolder) ? $imagewithfolder : '',
            'product_id'=> $request->id
           
    ]);

    
    }else{
    $storeImage = ProductsImage::create([
            'product_image' => isset($imagewithfolder) ? $imagewithfolder : '',
            'product_id'=> $request->id    
    ]);
    }
}
}
    $deleteMasterData = ProductMasterCategory::select()->where('product_id',$request->id)->delete();

    foreach ($request->mastercategory_id as $key => $value) {
        $storeMasterCategory = ProductMasterCategory::create([
            'mastercategory_id'=>$value,
            'product_id'=> $request->id
        ]);

    }

     $deleteMainData = ProductMainCategory::select()->where('product_id',$request->id)->delete();

    foreach ($request->maincategory_id as $key => $value1) {
        $storeMainCategory = ProductMainCategory::create([
            'maincategory_id'=>$value1,
            'product_id'=> $request->id
        ]);

    }

    $deletecategoryData = ProductCategory::select()->where('product_id',$request->id)->delete();

    if(isset($request->category_id)){
    foreach ($request->category_id as $key => $value2) {
        $storeCategory = ProductCategory::create([
            'category_id'=>$value2,
            'product_id'=> $request->id
        ]);

    }
    }

    $deletesubcategoryData = ProductSubCategory::select()->where('product_id',$request->id)->delete();

    if(isset($request->subcategory_id)){
    foreach ($request->subcategory_id as $key => $value3) {
        $storesubCategory = ProductSubCategory::create([
            'subcategory_id'=>$value3,
            'product_id'=> $request->id
        ]);

    }
    }
    $deletelocationData = ProductLocation::select()->where('product_id',$request->id)->delete();

    if($request->lat[0] != ''){
        foreach ($request->lat as $key => $value) {

        if($value != ''){
               $storeProductLocatin = ProductLocation::create([
                'lat'=>$value,
                'long' => $request->long[$key],
                'product_id'=> $request->id
                ]);
            }
        }
    }

    return redirect()->intended('product')->with('message','Update the data');
} 

    public function delete($id)
    {

        Product::find($id)->delete();
        ProductsImage::select()->where('product_id',$id)->delete();
        ProductMasterCategory::select()->where('product_id',$id)->delete();
        ProductMainCategory::select()->where('product_id',$id)->delete();
        ProductCategory::select()->where('product_id',$id)->delete();
        ProductSubCategory::select()->where('product_id',$id)->delete();
        ProductLocation::select()->where('product_id',$id)->delete();
        return back();
    }

}
