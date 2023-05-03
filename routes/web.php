<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('login');
// });



Route::get('dashboard',[App\Http\Controllers\LoginAuthController::class, 'dashboard'])->name('dashboard'); 
Route::get('/', [App\Http\Controllers\LoginAuthController::class, 'index'])->name('login');
Route::post('custom-login', [App\Http\Controllers\LoginAuthController::class, 'customLogin'])->name('login.post');
Route::get('signout', [App\Http\Controllers\LoginAuthController::class, 'logOut'])->name('logout');

// banner routes
Route::get('banner', [App\Http\Controllers\BannerController::class, 'index'])->name('banner.list');
Route::get('banner/add', [App\Http\Controllers\BannerController::class, 'create'])->name('banner.create');
Route::post('banner/bannerstore', [App\Http\Controllers\BannerController::class, 'store'])->name('banner.store');
Route::get('banner/show/{id}', [App\Http\Controllers\BannerController::class,'show'])->name('banner.show');
Route::delete('banner/{id}', [App\Http\Controllers\BannerController::class,'delete'])->name('banner.delete');
Route::get('banner/edit/{id}', [App\Http\Controllers\BannerController::class,'edit'])->name('banner.banneredit');
Route::post('bannerupdate', [App\Http\Controllers\BannerController::class,'update'])->name('banner.update');
Route::get('maincategorydropdown/{id}', [App\Http\Controllers\BannerController::class,'mainCategoryDropdown'])->name('banner.maincategorydropdown');
Route::get('categorydropdown/{id}', [App\Http\Controllers\BannerController::class,'categoryDropdown'])->name('banner.categorydropdown');
Route::get('subcategorydropdown/{id}', [App\Http\Controllers\BannerController::class,'subCategoryDropdown'])->name('banner.subcategorydropdown');


//mastercategory routes
Route::get('mastercategory', [App\Http\Controllers\MasterCategoryController::class, 'index'])->name('mastercategory.list');
Route::get('mastercategory/add', [App\Http\Controllers\MasterCategoryController::class, 'create'])->name('mastercategory.create');
Route::post('mastercategory/mastercategory', [App\Http\Controllers\MasterCategoryController::class, 'store'])->name('mastercategory.store');
Route::get('mastercategory/edit/{id}', [App\Http\Controllers\MasterCategoryController::class, 'edit'])->name('mastercategory.edit');
Route::post('mastercategoryupdate', [App\Http\Controllers\MasterCategoryController::class, 'update'])->name('mastercategory.update');
Route::get('mastercategory/show/{id}', [App\Http\Controllers\MasterCategoryController::class, 'show'])->name('mastercategory.show');
Route::delete('mastercategorydelete/{id}', [App\Http\Controllers\MasterCategoryController::class, 'delete'])->name('mastercategory.delete');

//maincategory routes
Route::get('maincategory', [App\Http\Controllers\MainCategoryController::class, 'index'])->name('maincategory.list');
Route::get('maincategory/add', [App\Http\Controllers\MainCategoryController::class, 'create'])->name('maincategory.create');
Route::post('maincategory/maincategory', [App\Http\Controllers\MainCategoryController::class, 'store'])->name('maincategory.store');
Route::get('maincategory/edit/{id}', [App\Http\Controllers\MainCategoryController::class, 'edit'])->name('maincategory.edit');
Route::post('maincategoryupdate', [App\Http\Controllers\MainCategoryController::class, 'update'])->name('maincategory.update');
Route::get('maincategory/show/{id}', [App\Http\Controllers\MainCategoryController::class, 'show'])->name('maincategory.show');
Route::delete('maincategory/{id}', [App\Http\Controllers\MainCategoryController::class, 'delete'])->name('maincategory.delete');

//category routes
Route::get('category', [App\Http\Controllers\CategoryController::class, 'index'])->name('category.list');
Route::get('category/add', [App\Http\Controllers\CategoryController::class, 'create'])->name('category.create');
Route::post('category/category', [App\Http\Controllers\CategoryController::class, 'store'])->name('category.store');
Route::get('category/edit/{id}', [App\Http\Controllers\CategoryController::class, 'edit'])->name('category.edit');
Route::post('categoryupdate', [App\Http\Controllers\CategoryController::class, 'update'])->name('category.update');
Route::get('category/show/{id}', [App\Http\Controllers\CategoryController::class, 'show'])->name('category.show');
Route::delete('categorydelete/{id}', [App\Http\Controllers\CategoryController::class, 'delete'])->name('category.delete');

//sub category routes
Route::get('subcategory', [App\Http\Controllers\SubCategoryController::class, 'index'])->name('subcategory.list');
Route::get('subcategory/add', [App\Http\Controllers\SubCategoryController::class, 'create'])->name('subcategory.create');
Route::post('subcategory/subcategory', [App\Http\Controllers\SubCategoryController::class, 'store'])->name('subcategory.store');
Route::get('subcategory/edit/{id}', [App\Http\Controllers\SubCategoryController::class, 'edit'])->name('subcategory.edit');
Route::post('subcategoryupdate', [App\Http\Controllers\SubCategoryController::class, 'update'])->name('subcategory.update');
Route::get('subcategory/show/{id}', [App\Http\Controllers\SubCategoryController::class, 'show'])->name('subcategory.show');
Route::delete('subcategorydelete/{id}', [App\Http\Controllers\SubCategoryController::class, 'delete'])->name('subcategory.delete');

//product routes
Route::get('product', [App\Http\Controllers\ProductController::class, 'index'])->name('product.list');
Route::get('product/add', [App\Http\Controllers\ProductController::class, 'create'])->name('product.create');
Route::post('product/product', [App\Http\Controllers\ProductController::class, 'store'])->name('product.store');
Route::get('product/edit/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('product.edit');
Route::post('productupdate', [App\Http\Controllers\ProductController::class, 'update'])->name('product.update');
Route::get('product/show/{id}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');
Route::delete('product/{id}', [App\Http\Controllers\ProductController::class, 'delete'])->name('product.delete');
Route::post('maincategoryproduct', [App\Http\Controllers\ProductController::class,'mainCategoryProduct'])->name('product.maincategoryproduct');
Route::post('categoryproduct', [App\Http\Controllers\ProductController::class,'categoryProduct'])->name('product.categoryproduct');
Route::post('subcategoryproduct', [App\Http\Controllers\ProductController::class,'subCategoryProduct'])->name('product.subcategoryproduct');


//offer product routes
Route::get('offerproduct', [App\Http\Controllers\OfferProductController::class, 'index'])->name('offerproduct.list');
Route::get('offerproduct/add', [App\Http\Controllers\OfferProductController::class, 'create'])->name('offerproduct.create');
Route::get('allproducts', [App\Http\Controllers\OfferProductController::class, 'allProduct'])->name('offerproduct.allproduct');
Route::post('offerproduct/offerproduct', [App\Http\Controllers\OfferProductController::class, 'store'])->name('offerproduct.store');
Route::get('offerproduct/edit/{id}', [App\Http\Controllers\OfferProductController::class, 'edit'])->name('offerproduct.edit');
Route::get('selectedproducts', [App\Http\Controllers\OfferProductController::class, 'selectedProducts'])->name('offerproduct.selectedproducts');
Route::post('offerproductupdate', [App\Http\Controllers\OfferProductController::class, 'update'])->name('offerproduct.update');
Route::delete('offerproduct/{id}', [App\Http\Controllers\OfferProductController::class, 'delete'])->name('offerproduct.delete');


//Explore routes
Route::get('explore', [App\Http\Controllers\ExploreController::class, 'index'])->name('explore.list');
Route::get('explore/add', [App\Http\Controllers\ExploreController::class, 'create'])->name('explore.create');
Route::post('explore/explorestore', [App\Http\Controllers\ExploreController::class, 'store'])->name('explore.store');
Route::delete('explore/{id}', [App\Http\Controllers\ExploreController::class,'delete'])->name('explore.delete');
Route::get('explore/edit/{id}', [App\Http\Controllers\ExploreController::class,'edit'])->name('explore.exploreedit');
Route::post('exploreupdate', [App\Http\Controllers\ExploreController::class,'update'])->name('explore.update');
Route::post('explorecategorystore', [App\Http\Controllers\ExploreController::class,'exploreCategoryStore'])->name('explore.explorecategorystore');
Route::delete('explorecategorydelete/{id}', [App\Http\Controllers\ExploreController::class,'exploreCategoryDelete'])->name('explorecategorydelete.delete');
Route::get('maincategorydropdownajax/{id}', [App\Http\Controllers\ExploreController::class,'maincategoryDropdownAjax'])->name('explore.maincategorydropdownajax');
Route::get('categorydropdownajax/{id}', [App\Http\Controllers\ExploreController::class,'categoryDropdownAjax'])->name('explore.categorydropdownajax');
Route::get('subcategorydropdownajax/{id}', [App\Http\Controllers\ExploreController::class,'subCategoryDropdownAjax'])->name('explore.subcategorydropdownajax');

//Banner Category routes
Route::get('bannercategory', [App\Http\Controllers\BannerCategoryController::class, 'index'])->name('bannercategory.list');
Route::get('bannercategory/add', [App\Http\Controllers\BannerCategoryController::class, 'create'])->name('bannercategory.create');
Route::post('bannercategory/bannercategorystore', [App\Http\Controllers\BannerCategoryController::class, 'store'])->name('bannercategory.store');
Route::delete('bannercategory/{id}', [App\Http\Controllers\BannerCategoryController::class, 'delete'])->name('bannercategory.delete');
Route::get('bannermaincategorydropdown/{id}', [App\Http\Controllers\BannerCategoryController::class,'bannerMaincategoryDropdown'])->name('banner.bannermaincategorydropdown');
Route::get('bannercategorydropdown/{id}', [App\Http\Controllers\BannerCategoryController::class,'bannerCategoryDropdown'])->name('banner.bannercategorydropdown');
Route::get('bannersubcategorydropdown/{id}', [App\Http\Controllers\BannerCategoryController::class,'bannerSubCategoryDropdown'])->name('banner.bannersubcategorydropdown');






