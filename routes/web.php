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
Route::get('banneradd', [App\Http\Controllers\BannerController::class, 'create'])->name('banner.create');
Route::post('banner/bannerstore', [App\Http\Controllers\BannerController::class, 'store'])->name('banner.store');
Route::get('bannershow/{id}', [App\Http\Controllers\BannerController::class,'show'])->name('banner.show');
Route::delete('banner/{id}', [App\Http\Controllers\BannerController::class,'delete'])->name('banner.delete');
Route::get('banneredit/{id}', [App\Http\Controllers\BannerController::class,'edit'])->name('banner.banneredit');
Route::post('bannerupdate', [App\Http\Controllers\BannerController::class,'update'])->name('banner.update');

//mastercategory routes
Route::get('mastercategory', [App\Http\Controllers\MasterCategoryController::class, 'index'])->name('mastercategory.list');
Route::get('mastercategoryadd', [App\Http\Controllers\MasterCategoryController::class, 'create'])->name('mastercategory.create');
Route::post('mastercategory/mastercategory', [App\Http\Controllers\MasterCategoryController::class, 'store'])->name('mastercategory.store');
Route::get('mastercategoryedit/{id}', [App\Http\Controllers\MasterCategoryController::class, 'edit'])->name('mastercategory.edit');
Route::post('mastercategoryupdate', [App\Http\Controllers\MasterCategoryController::class, 'update'])->name('mastercategory.update');
Route::get('mastercategoryshow/{id}', [App\Http\Controllers\MasterCategoryController::class, 'show'])->name('mastercategory.show');
Route::delete('mastercategorydelete/{id}', [App\Http\Controllers\MasterCategoryController::class, 'delete'])->name('mastercategory.delete');

//maincategory routes
Route::get('maincategory', [App\Http\Controllers\MainCategoryController::class, 'index'])->name('maincategory.list');
Route::get('maincategoryadd', [App\Http\Controllers\MainCategoryController::class, 'create'])->name('maincategory.create');
Route::post('maincategory/maincategory', [App\Http\Controllers\MainCategoryController::class, 'store'])->name('maincategory.store');
Route::get('maincategoryedit/{id}', [App\Http\Controllers\MainCategoryController::class, 'edit'])->name('maincategory.edit');
Route::post('maincategoryupdate', [App\Http\Controllers\MainCategoryController::class, 'update'])->name('maincategory.update');
Route::get('maincategoryshow/{id}', [App\Http\Controllers\MainCategoryController::class, 'show'])->name('maincategory.show');
Route::delete('maincategory/{id}', [App\Http\Controllers\MainCategoryController::class, 'delete'])->name('maincategory.delete');

//category routes
Route::get('category', [App\Http\Controllers\CategoryController::class, 'index'])->name('category.list');
Route::get('categoryadd', [App\Http\Controllers\CategoryController::class, 'create'])->name('category.create');
Route::post('category/category', [App\Http\Controllers\CategoryController::class, 'store'])->name('category.store');
Route::get('categoryedit/{id}', [App\Http\Controllers\CategoryController::class, 'edit'])->name('category.edit');
Route::post('categoryupdate', [App\Http\Controllers\CategoryController::class, 'update'])->name('category.update');
Route::get('categoryshow/{id}', [App\Http\Controllers\CategoryController::class, 'show'])->name('category.show');
Route::delete('categorydelete/{id}', [App\Http\Controllers\CategoryController::class, 'delete'])->name('category.delete');

//sub category routes
Route::get('subcategory', [App\Http\Controllers\SubCategoryController::class, 'index'])->name('subcategory.list');
Route::get('subcategoryadd', [App\Http\Controllers\SubCategoryController::class, 'create'])->name('subcategory.create');
Route::post('subcategory/subcategory', [App\Http\Controllers\SubCategoryController::class, 'store'])->name('subcategory.store');
Route::get('subcategoryedit/{id}', [App\Http\Controllers\SubCategoryController::class, 'edit'])->name('subcategory.edit');
Route::post('subcategoryupdate', [App\Http\Controllers\SubCategoryController::class, 'update'])->name('subcategory.update');
Route::get('subcategoryshow/{id}', [App\Http\Controllers\SubCategoryController::class, 'show'])->name('subcategory.show');
Route::delete('subcategorydelete/{id}', [App\Http\Controllers\SubCategoryController::class, 'delete'])->name('subcategory.delete');

//product routes
Route::get('product', [App\Http\Controllers\ProductController::class, 'index'])->name('product.list');
Route::get('productadd', [App\Http\Controllers\ProductController::class, 'create'])->name('product.create');
Route::post('product/product', [App\Http\Controllers\ProductController::class, 'store'])->name('product.store');
Route::get('productedit/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('product.edit');
Route::post('productupdate', [App\Http\Controllers\ProductController::class, 'update'])->name('product.update');
Route::get('productshow/{id}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');
Route::delete('product/{id}', [App\Http\Controllers\ProductController::class, 'delete'])->name('product.delete');


