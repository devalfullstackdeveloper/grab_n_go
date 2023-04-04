<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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


Route::post('/register' , [App\Http\Controllers\Auth\UserAuthController::class,'register']);
Route::post('/login' , [App\Http\Controllers\Auth\UserAuthController::class,'login']);
Route::post('/facebook_google_registration', [App\Http\Controllers\Auth\FaceBookGoogleController::class, 'faceBookGoogleRegistarion']);
Route::post('/guestuser', [App\Http\Controllers\Auth\GuestUserController::class, 'guestUser']);

Route::apiResource('products', 'App\Http\Controllers\Api\ProductController')->middleware('auth:api');

Route::group(['middleware' => ['auth:api']], function(){

	//category routes
	Route::get('mastercategory',[App\Http\Controllers\Api\CategoryController::class, 'masterCategory']);
	Route::post('maincategory',[App\Http\Controllers\Api\CategoryController::class, 'mainCategory']);
	Route::post('category',[App\Http\Controllers\Api\CategoryController::class, 'category']);
	Route::post('subcategory',[App\Http\Controllers\Api\CategoryController::class, 'subCategory']);
	Route::get('mastermaincategory',[App\Http\Controllers\Api\CategoryController::class, 'masterMainCategory']);

	//address routes
	Route::post('addaddress',[App\Http\Controllers\Api\AddressController::class, 'addAddress']);
	Route::post('getaddress',[App\Http\Controllers\Api\AddressController::class, 'getAddress']);
	Route::post('updateaddress',[App\Http\Controllers\Api\AddressController::class, 'updateAddress']);
	Route::post('deleteaddress',[App\Http\Controllers\Api\AddressController::class, 'deleteAddress']);

	//home routes
	Route::get('home',[App\Http\Controllers\Api\HomeController::class, 'home']);



	//master_main_category_subcategory_ product routes


//Cart route
	Route::post('storecart',[App\Http\Controllers\Api\CartController::class, 'storeCart']);
	Route::get('showcart',[App\Http\Controllers\Api\CartController::class, 'showCart']);
	Route::get('usercart',[App\Http\Controllers\Api\CartController::class, 'userCart']);
  
	Route::post('productfrommaster',[App\Http\Controllers\Api\ProductCategoryController::class, 'productFromMaster']);
	Route::post('productfrommain',[App\Http\Controllers\Api\ProductCategoryController::class, 'productFromMain']);
	Route::post('productfromcategory',[App\Http\Controllers\Api\ProductCategoryController::class, 'productFromCategory']);
	Route::post('productfromsubcategory',[App\Http\Controllers\Api\ProductCategoryController::class, 'productFromSubCategory']);

});