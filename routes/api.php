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

	//address routes
	Route::post('addaddress',[App\Http\Controllers\Api\AddressController::class, 'addAddress']);
	Route::post('getaddress',[App\Http\Controllers\Api\AddressController::class, 'getAddress']);

});

