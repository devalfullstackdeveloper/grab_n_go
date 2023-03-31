<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\FaceBookGoogleController;
use App\Http\Controllers\Api\ProductsApiController; 
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
  

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/register', 'Auth\UserAuthController@register');
Route::post('/register' , [UserAuthController::class,'register']);
Route::post('/login' , [UserAuthController::class,'login']);
Route::post('/facebook_google_registration', [FaceBookGoogleController::class, 'faceBookGoogleRegistarion']);

Route::apiResource('products', 'App\Http\Controllers\Api\ProductController')->middleware('auth:api');

Route::group(['middleware' => ['auth:api']], function(){

//product routes
Route::get('mastercategory',[App\Http\Controllers\Api\CategoryController::class, 'masterCategory']);
Route::post('maincategory',[App\Http\Controllers\Api\CategoryController::class, 'mainCategory']);
Route::post('category',[App\Http\Controllers\Api\CategoryController::class, 'category']);
Route::post('subcategory',[App\Http\Controllers\Api\CategoryController::class, 'subCategory']);



//master_main_category product routes
Route::get('productList',[App\Http\Controllers\Api\ProductController::class, 'productList']);
Route::post('productfrommaster',[App\Http\Controllers\Api\ProductMasterController::class, 'productFromMaster']);
Route::post('productfrommain',[App\Http\Controllers\Api\ProductMasterController::class, 'productFromMain']);
Route::post('productfromcategory',[App\Http\Controllers\Api\ProductMasterController::class, 'productFromCategory']);




});