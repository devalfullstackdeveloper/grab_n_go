<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\MasterCategory;
use App\Models\MainCategory;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\MasterMainCategory;
use App\Models\MainCategoryCategory;
use App\Models\CategorySubCategory;
use App\Models\ProductAllCategory;
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
            $data[$key]['packet'] = $value->packet;
            $data[$key]['status'] = $value->status;

            $getProductMasterCatName = array();
            $getProductMasterCategory = ProductAllCategory::select('products_all_category.mastercategory_id', 'mastercategory.master_category_name')
                ->distinct()
                ->join('mastercategory', 'mastercategory.id', '=', 'products_all_category.mastercategory_id')
                ->where('product_id', $value->id)->get();

            foreach ($getProductMasterCategory as $k => $value1) {
                $getProductMasterCatName[$k] = $value1->master_category_name;
            }
            $data[$key]['master_category_name'] = implode(',', $getProductMasterCatName);

            $getProductMainCatName = array();
            $getProductMainCategory = ProductAllCategory::select('products_all_category.maincategory_id', 'maincategory.main_category_name')
                ->distinct()
                ->join('maincategory', 'maincategory.id', '=', 'products_all_category.maincategory_id')
                ->where('product_id', $value->id)->get();

            foreach ($getProductMainCategory as $k1 => $value2) {
                $getProductMainCatName[$k1] = $value2->main_category_name;
            }
            $data[$key]['main_category_name'] = implode(',', $getProductMainCatName);

            $getProductCatName = array();
            $getProductCategory = ProductAllCategory::select('products_all_category.category_id', 'category.category_name')
                ->distinct()
                ->join('category', 'category.id', '=', 'products_all_category.category_id')
                ->where('product_id', $value->id)->get();

            foreach ($getProductCategory as $k2 => $value3) {
                $getProductCatName[$k2] = $value3->category_name;
            }
            $data[$key]['category_name'] = implode(',', $getProductCatName);

            $getProductSubCatName = array();
            $getProductSubCategory = ProductAllCategory::select('products_all_category.subcategory_id', 'subcategory.sub_category_name')
                ->distinct()
                ->join('subcategory', 'subcategory.id', '=', 'products_all_category.subcategory_id')
                ->where('product_id', $value->id)->get();

            foreach ($getProductSubCategory as $k3 => $value4) {
                $getProductSubCatName[$k3] = $value4->sub_category_name;
            }
            $data[$key]['sub_category_name'] = implode(',', $getProductSubCatName);

            $getProductLatName = array();
            $getProductLongName = array();
            $getProductLatLong = ProductLocation::select()
                ->where('product_id', $value->id)->get();

            foreach ($getProductLatLong as $k7 => $value8) {
                $getProductLatName[$k7] = $value8->lat;
                $getProductLongName[$k7] = $value8->long;
            }
            $data[$key]['lat'] = implode(',', $getProductLatName);
            $data[$key]['long'] = implode(',', $getProductLongName);
        }
        return view('product.product', compact('data'));

    }
    public function create()
    {

        $masterCategorydata = MasterCategory::select('id', 'master_category_name')->where('status', 1)->get();
        return view('product.productadd', compact('masterCategorydata'));

    }
    //maincategory dropdown filter
    public function mainCategoryProduct(Request $request)
    {
        $mainCategoryData = array();
        foreach ($request['mastercategory_id'] as $ids) {
            $mainCategory = MasterMainCategory::select('mastermaincategory.*', 'maincategory.*')
                ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
                ->where("mastercategory_id", $ids)
                ->where('maincategory.status', 1)
                ->get()
                ->toArray();
            $mainCategoryData[] = $mainCategory;
        }
        return json_encode($mainCategoryData);
    }
    //category dropdown filter
    public function categoryProduct(Request $request)
    {
        $categoryData = array();
        foreach ($request['maincategory_id'] as $ids) {
            $category = MainCategoryCategory::select('maincategorycategory.*', 'category.*')
                ->join('category', 'category.id', '=', 'maincategorycategory.category_id')
                ->where("maincategory_id", $ids)
                ->where('category.status', 1)
                ->get()->toArray();
            $categoryData[] = $category;
        }
        return json_encode($categoryData);
    }
//  //sub-category dropdown filter
    public function subCategoryProduct(Request $request)
    {
        $subCategoryData = array();
        foreach ($request['category_id'] as $ids) {
            $subCategory = CategorySubCategory::select('categorysubcategory.*', 'subcategory.*')
                ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
                ->where("category_id", $ids)
                ->where('subcategory.status', 1)
                ->get();
            $subCategoryData[] = $subCategory;
        }
        return json_encode($subCategoryData);
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            "product_name" => 'required|string',
            'product_image' => 'required',
            'product_image.*' => 'mimes:jpg,png,jpeg,svg',
            "product_details" => 'required|min:15',
            "product_price" => 'required',
            "mastercategory_id" => "required|array|min:1",
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
            'packet' => $request->packet,
            'status' => $request->status,

        ]);

        $path = public_path('productimage');

        foreach ($request->product_image as $prodimage) {

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
                $imageName = time() . rand(1, 50) . '.' . $prodimage->extension();
                $prodimage->move(public_path('productimage'), $imageName);
                $imagewithfolder = 'public/productimage/' . $imageName;
                // $prodimage = array();

            } else {
                $imageName = time() . rand(1, 50) . '.' . $prodimage->extension();
                $prodimage->move(public_path('productimage'), $imageName);
                $imagewithfolder = 'public/productimage/' . $imageName;
                // $prodimage = array();

            }

            $imagedata = ProductsImage::create([
                'product_image' => $imagewithfolder,
                'product_id' => $data->id,
            ]);
        }

        foreach ($request->mastercategory_id as $mk => $masterCatId) {
            $mastermaindata = MasterMainCategory::where('mastercategory_id', $masterCatId)
                ->pluck('maincategory_id')->toArray();
            if (count($mastermaindata) > 0 && $request->maincategory_id != 0) {
                $matchMainCat = array_intersect($mastermaindata, $request->maincategory_id);
                if (count($matchMainCat) > 0) {
                    foreach ($matchMainCat as $mainCatId) {
                        if (count($matchMainCat) > 0) {
                            $maincatdata = MainCategoryCategory::where('maincategory_id', $mainCatId)
                                ->pluck('category_id')->toArray();
                            if (count($maincatdata) > 0 && $request->category_id != 0) {
                                $matchCat = array_intersect($maincatdata, $request->category_id);
                                foreach ($matchCat as $catId) {
                                    if (count($matchCat) > 0) {
                                        $subdata = CategorySubCategory::where('category_id', $catId)
                                            ->pluck('subcategory_id')->toArray();
                                        if (count($subdata) > 0 && $request->subcategory_id != 0) {
                                            $matchSub = array_intersect($subdata, $request->subcategory_id);
                                            foreach ($matchSub as $subId) {
                                                $storeProductMasterCategory = ProductAllCategory::create([
                                                    'mastercategory_id' => $masterCatId,
                                                    'maincategory_id' => $mainCatId,
                                                    'category_id' => $catId,
                                                    'subcategory_id' => $subId,
                                                    'product_id' => $data->id,
                                                ]);
                                            }
                                        } else {
                                            $storeProductMasterCategory = ProductAllCategory::create([
                                                'mastercategory_id' => $masterCatId,
                                                'maincategory_id' => $mainCatId,
                                                'category_id' => $catId,
                                                'subcategory_id' => 0,
                                                'product_id' => $data->id,
                                            ]);
                                        }
                                    } else {
                                        $storeProductMasterCategory = ProductAllCategory::create([
                                            'mastercategory_id' => $masterCatId,
                                            'maincategory_id' => $mainCatId,
                                            'category_id' => $catId,
                                            'subcategory_id' => 0,
                                            'product_id' => $data->id,
                                        ]);
                                    }
                                }
                            } else {
                                $storeProductMasterCategory = ProductAllCategory::create([
                                    'mastercategory_id' => $masterCatId,
                                    'maincategory_id' => $mainCatId,
                                    'category_id' => 0,
                                    'subcategory_id' => 0,
                                    'product_id' => $data->id,
                                ]);
                            }
                        } else {
                            $storeProductMasterCategory = ProductAllCategory::create([
                                'mastercategory_id' => $masterCatId,
                                'maincategory_id' => $mainCatId,
                                'category_id' => 0,
                                'subcategory_id' => 0,
                                'product_id' => $data->id,
                            ]);
                        }
                    }
                } else {
                    $storeProductMainCategory = ProductAllCategory::create([
                        'mastercategory_id' => $masterCatId,
                        'maincategory_id' => 0,
                        'category_id' => 0,
                        'subcategory_id' => 0,
                        'product_id' => $data->id,
                    ]);
                }
            } else {
                $storeProductMainCategory = ProductAllCategory::create([
                    'mastercategory_id' => $masterCatId,
                    'maincategory_id' => 0,
                    'category_id' => 0,
                    'subcategory_id' => 0,
                    'product_id' => $data->id,
                ]);
            }
        }

        foreach ($request->lat as $key => $value) {
            $storeProductLocatin = ProductLocation::create([
                'lat' => $value,
                'long' => $request->long[$key],
                'product_id' => $data->id,
            ]);
        }

        return redirect()->intended('product')->with('message', 'Data stored');

    }

    public function show(Request $request, $id)
    {
        $getProduct = Product::select()->where('id', $id)->first();

        $data = array();

        $data['product_name'] = $getProduct->product_name;
        $data['product_details'] = $getProduct->product_details;
        $data['product_price'] = $getProduct->product_price;
        $data['quantity'] = $getProduct->quantity;
        $data['point'] = $getProduct->point;
        $data['sale'] = $getProduct->sale;
        $data['sale_price'] = $getProduct->sale_price;
        $data['packet'] = $getProduct->packet;
        $data['status'] = $getProduct->status;

        $getProductMasterCatName = array();
        $getProductMasterCategory = ProductAllCategory::select('products_all_category.mastercategory_id', 'mastercategory.master_category_name')
            ->distinct()
            ->join('mastercategory', 'mastercategory.id', '=', 'products_all_category.mastercategory_id')
            ->where('product_id', $id)->get();

        foreach ($getProductMasterCategory as $k => $value1) {
            $getProductMasterCatName[$k] = $value1->master_category_name;
        }
        $data['master_category_name'] = implode(',', $getProductMasterCatName);

        $getProductMainCatName = array();
        $getProductMainCategory = ProductAllCategory::select('products_all_category.maincategory_id', 'maincategory.main_category_name')
            ->distinct()
            ->join('maincategory', 'maincategory.id', '=', 'products_all_category.maincategory_id')
            ->where('product_id', $id)->get();

        foreach ($getProductMainCategory as $k1 => $value2) {
            $getProductMainCatName[$k1] = $value2->main_category_name;
        }
        $data['main_category_name'] = implode(',', $getProductMainCatName);

        $getProductCatName = array();
        $getProductCategory = ProductAllCategory::select('products_all_category.category_id', 'category.category_name')
            ->distinct()
            ->join('category', 'category.id', '=', 'products_all_category.category_id')
            ->where('product_id', $id)->get();

        foreach ($getProductCategory as $k2 => $value3) {
            $getProductCatName[$k2] = $value3->category_name;
        }
        $data['category_name'] = implode(',', $getProductCatName);

        $getProductSubCatName = array();
        $getProductSubCategory = ProductAllCategory::select('products_all_category.subcategory_id', 'subcategory.sub_category_name')
            ->distinct()
            ->join('subcategory', 'subcategory.id', '=', 'products_all_category.subcategory_id')
            ->where('product_id', $id)->get();

        foreach ($getProductSubCategory as $k3 => $value4) {
            $getProductSubCatName[$k3] = $value4->sub_category_name;
        }
        $data['sub_category_name'] = implode(',', $getProductSubCatName);

        $getProductImageName = array();
        $getProductImage = ProductsImage::select()
            ->where('product_id', $id)->get();

        foreach ($getProductImage as $k4 => $value5) {

            $getProductImageName[$k4] = $value5->product_image;
        }
        $data['product_image'] = $getProductImageName;

        $getProductLatName = array();
        $getProductLongName = array();
        $getProductLatLong = ProductLocation::select()
            ->where('product_id', $id)->get();

        foreach ($getProductLatLong as $k7 => $value8) {
            $getProductLatName[$k7] = $value8->lat;
            $getProductLongName[$k7] = $value8->long;
        }
        $data['lat'] = implode(',', $getProductLatName);
        $data['long'] = implode(',', $getProductLongName);

        return view('product.productshow', compact('data'));
    }

    public function edit($id)
    {

        $getProduct = Product::select()->where('id', $id)->first();

        $data = array();

        $data['product_id'] = $getProduct->id;
        $data['product_name'] = $getProduct->product_name;
        $data['product_details'] = $getProduct->product_details;
        $data['product_price'] = $getProduct->product_price;
        $data['quantity'] = $getProduct->quantity;
        $data['point'] = $getProduct->point;
        $data['sale'] = $getProduct->sale;
        $data['sale_price'] = $getProduct->sale_price;
        $data['packet'] = $getProduct->packet;
        $data['status'] = $getProduct->status;

        //fetch the master category
        $getProductMasterCatName = array();
        $mastercategoryId = array();
        $getProductMasterCategory = ProductAllCategory::select()
            ->join('mastercategory', 'mastercategory.id', '=', 'products_all_category.mastercategory_id')
            ->where('product_id', $id)->get();

        foreach ($getProductMasterCategory as $k => $getProductMasterCategoryValue) {
            $getProductMasterCatName[$getProductMasterCategoryValue->mastercategory_id] = $getProductMasterCategoryValue->master_category_name;
            $mastercategoryId[] = $getProductMasterCategoryValue->mastercategory_id;
        }

        $data["master_category_name"] = $getProductMasterCatName;
        $data["master_category_id"] = $mastercategoryId;

        $masterCategoryData = MasterCategory::select('id', 'master_category_name')->where('status', 1)->get()->toArray();

        //fetch the main category
        $getProductMainCatName = array();
        $maincategoryId = array();
        $getProductMainCategory = ProductAllCategory::select('maincategory.id', 'maincategory.main_category_name')->distinct()
            ->join('maincategory', 'maincategory.id', '=', 'products_all_category.maincategory_id')
            ->where('product_id', $id)
            ->get()
            ->toArray();

        $mainCategoryData = ProductAllCategory::select('products_all_category.mastercategory_id', 'mastermaincategory.maincategory_id', 'maincategory.*')->distinct()
            ->join('mastermaincategory', 'mastermaincategory.mastercategory_id', '=', 'products_all_category.mastercategory_id')
            ->join('maincategory', 'maincategory.id', '=', 'mastermaincategory.maincategory_id')
            ->where('product_id', $id)
            ->get()
            ->toArray();

//    echo "<pre>";
//     print_r($mainCategoryData);
//     echo "</pre>";
//     exit();

        foreach ($getProductMainCategory as $getProductMainCategoryValue) {
            $getProductMainCatName[$getProductMainCategoryValue['id']] = $getProductMainCategoryValue['main_category_name'];
            $maincategoryId[] = $getProductMainCategoryValue['id'];
        }

        $data["main_category_name"] = $getProductMainCatName;
        $data["main_category_id"] = $maincategoryId;

        //fetch the category
        $getProductCatName = array();
        $categoryId = array();
        $getProductCategory = ProductAllCategory::select('category.id', 'category.category_name')->distinct()
            ->join('category', 'category.id', '=', 'products_all_category.category_id')
            ->where('product_id', $id)
            ->get()
            ->toArray();

        $categoryData = ProductAllCategory::select('products_all_category.maincategory_id', 'maincategorycategory.category_id', 'category.*')->distinct()
            ->join('maincategorycategory', 'maincategorycategory.maincategory_id', '=', 'products_all_category.maincategory_id')
            ->join('category', 'category.id', '=', 'maincategorycategory.category_id')
            ->where('product_id', $id)
            ->get()
            ->toArray();

        foreach ($getProductCategory as $getProductCategoryValue) {
            $getProductCatName[$getProductCategoryValue['id']] = $getProductCategoryValue['category_name'];
            $categoryId[] = $getProductCategoryValue['id'];
        }

        $data["category_name"] = $getProductCatName;
        $data["category_id"] = $categoryId;

        //fetch the subcategory
        $getProductSubCategoryName = array();
        $subCategoryId = array();
        $getProductSubCategory = ProductAllCategory::select('subcategory.id', 'subcategory.sub_category_name')
            ->join('subcategory', 'subcategory.id', '=', 'products_all_category.subcategory_id')
            ->where('product_id', $id)
            ->get()
            ->toArray();

        $subCategoryData = ProductAllCategory::select('products_all_category.category_id', 'categorysubcategory.subcategory_id', 'subcategory.*')->distinct()
            ->join('categorysubcategory', 'categorysubcategory.category_id', '=', 'products_all_category.category_id')
            ->join('subcategory', 'subcategory.id', '=', 'categorysubcategory.subcategory_id')
            ->where('product_id', $id)
            ->get()
            ->toArray();

        foreach ($getProductSubCategory as $getProductSubCategoryValue) {

            $getProductSubCategoryName[$getProductSubCategoryValue['id']] = $getProductSubCategoryValue['sub_category_name'];
            $subCategoryId[] = $getProductSubCategoryValue['id'];
        }

        $data["sub_category_name"] = $getProductSubCategoryName;
        $data["subcategory_id"] = $subCategoryId;

        $getProductLatName = array();
        $getProductLongName = array();
        $getProductLatLongName = array();

        $getProductLatLong = ProductLocation::select()
            ->where('product_id', $id)->get();

        foreach ($getProductLatLong as $k7 => $value8) {
            if ($value8->lat != '') {

                $getProductLatName[$k7] = $value8->lat;
                $getProductLongName[$k7] = $value8->long;

                $getProductLatLongName[$k7] = "<div class='entry input-group'>
                                        <input type='text' class='form-control' name='lat[]' value='" . $value8->lat . "'>" .
                "<input type='text' class='form-control' name='long[]' value='" . $value8->long . "'>" .
                    "<span class='input-group-btn'>
                                        <button class='btn btn-remove btn-default' type='button'><i class='fa fa-minus' aria-hidden='true'></i></button>
                                        </span>
                                        </div>";

            }

        }

        $data['lat'] = $getProductLatName;
        $data['long'] = $getProductLongName;
        $data['latlong'] = $getProductLatLongName;

        $LatLongData = ProductLocation::select('id', 'lat', 'long')->get()->toArray();
        return view('product.productedit', compact('data', 'masterCategoryData', 'mainCategoryData', 'categoryData', 'subCategoryData'));
    }

    public function update(Request $request)
    {

        $this->validate($request, [
            "product_name" => 'required|string',
            "product_details" => 'required|string',
            "product_price" => 'required',
            "mastercategory_id" => "required|array|min:1",
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
            "product_price" => isset($request->product_price) ? $request->product_price : '',
            "status" => isset($request->status) ? $request->status : '',
            "quantity" => isset($request->quantity) ? $request->quantity : '',
            "sale" => isset($request->sale) ? $request->sale : '',
            "sale_price" => isset($request->sale_price) ? $request->sale_price : null,
            "packet" => isset($request->packet) ? $request->packet : '',
            "point" => isset($request->point) ? $request->point : '',
        ]);

        $path = public_path('productimage');

        if (isset($request->product_image)) {
            $deleteImageData = ProductsImage::select()->where('product_id', $request->id)->delete();

            foreach ($request->product_image as $prodimage) {

                if ($_FILES['product_image']['name'] != '') {
                    if (!File::isDirectory($path)) {
                        File::makeDirectory($path, 0777, true, true);
                        $imageName = time() . rand(1, 50) . '.' . $prodimage->extension();
                        $prodimage->move(public_path('productimage'), $imageName);
                        $imagewithfolder = 'public/productimage/' . $imageName;

                    } else {
                        $imageName = time() . rand(1, 50) . '.' . $prodimage->extension();
                        $prodimage->move(public_path('productimage'), $imageName);
                        $imagewithfolder = 'public/productimage/' . $imageName;
                    }

                    $storeImage = ProductsImage::create([
                        'product_image' => isset($imagewithfolder) ? $imagewithfolder : '',
                        'product_id' => $request->id,

                    ]);

                } else {
                    $storeImage = ProductsImage::create([
                        'product_image' => isset($imagewithfolder) ? $imagewithfolder : '',
                        'product_id' => $request->id,
                    ]);
                }
            }
        }
        $deleteMasterData = ProductAllCategory::select()->where('product_id', $request->id)->delete();
        foreach ($request->mastercategory_id as $masterCatId) {
            $mastermaindata = MasterMainCategory::where('mastercategory_id', $masterCatId)
                ->pluck('maincategory_id')->toArray();
            if (count($mastermaindata) > 0 && $request->maincategory_id != 0) {
                $matchMainCat = array_intersect($mastermaindata, $request->maincategory_id);
                if (count($matchMainCat) > 0) {
                    foreach ($matchMainCat as $mainCatId) {
                        if (count($matchMainCat) > 0) {
                            $maincatdata = MainCategoryCategory::where('maincategory_id', $mainCatId)
                                ->pluck('category_id')->toArray();
                            if (count($maincatdata) > 0 && $request->category_id != 0) {
                                $matchCat = array_intersect($maincatdata, $request->category_id);

                                foreach ($matchCat as $catId) {
                                    if (count($matchCat) > 0) {
                                        $subdata = CategorySubCategory::where('category_id', $catId)
                                            ->pluck('subcategory_id')->toArray();
                                        if (count($subdata) > 0 && $request->subcategory_id != 0) {
                                            $matchSub = array_intersect($subdata, $request->subcategory_id);

                                            foreach ($matchSub as $subId) {

                                                $storeProductMasterCategory = ProductAllCategory::create([
                                                    'mastercategory_id' => $masterCatId,
                                                    'maincategory_id' => $mainCatId,
                                                    'category_id' => $catId,
                                                    'subcategory_id' => $subId,
                                                    'product_id' => $request->id,
                                                ]);
                                            }
                                        } else {
                                            $storeProductMasterCategory = ProductAllCategory::create([
                                                'mastercategory_id' => $masterCatId,
                                                'maincategory_id' => $mainCatId,
                                                'category_id' => $catId,
                                                'subcategory_id' => 0,
                                                'product_id' => $request->id,
                                            ]);
                                        }
                                    } else {

                                        $storeProductMasterCategory = ProductAllCategory::create([
                                            'mastercategory_id' => $masterCatId,
                                            'maincategory_id' => $mainCatId,
                                            'category_id' => $catId,
                                            'subcategory_id' => 0,
                                            'product_id' => $request->id,
                                        ]);
                                    }
                                }
                            } else {

                                $storeProductMasterCategory = ProductAllCategory::create([
                                    'mastercategory_id' => $masterCatId,
                                    'maincategory_id' => $mainCatId,
                                    'category_id' => 0,
                                    'subcategory_id' => 0,
                                    'product_id' => $request->id,
                                ]);
                            }
                        } else {

                            $storeProductMasterCategory = ProductAllCategory::create([
                                'mastercategory_id' => $masterCatId,
                                'maincategory_id' => $mainCatId,
                                'category_id' => 0,
                                'subcategory_id' => 0,
                                'product_id' => $request->id,
                            ]);
                        }
                    }
                } else {

                    $storeProductMainCategory = ProductAllCategory::create([
                        'mastercategory_id' => $masterCatId,
                        'maincategory_id' => 0,
                        'category_id' => 0,
                        'subcategory_id' => 0,
                        'product_id' => $request->id,
                    ]);
                }
            } else {

                $storeProductMainCategory = ProductAllCategory::create([
                    'mastercategory_id' => $masterCatId,
                    'maincategory_id' => 0,
                    'category_id' => 0,
                    'subcategory_id' => 0,
                    'product_id' => $request->id,
                ]);
            }
        }

        $deletelocationData = ProductLocation::select()->where('product_id', $request->id)->delete();

        if ($request->lat[0] != '') {
            foreach ($request->lat as $key => $value) {

                if ($value != '') {
                    $storeProductLocatin = ProductLocation::create([
                        'lat' => $value,
                        'long' => $request->long[$key],
                        'product_id' => $request->id,
                    ]);
                }
            }
        }

        return redirect()->intended('product')->with('message', 'Update the data');
    }

    public function delete($id)
    {

        Product::find($id)->delete();
        ProductsImage::select()->where('product_id', $id)->delete();
        ProductMasterCategory::select()->where('product_id', $id)->delete();
        ProductMainCategory::select()->where('product_id', $id)->delete();
        ProductCategory::select()->where('product_id', $id)->delete();
        ProductSubCategory::select()->where('product_id', $id)->delete();
        ProductLocation::select()->where('product_id', $id)->delete();
        return back();
    }
}
