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
	Route::post('productfrommaster',[App\Http\Controllers\Api\ProductCategoryController::class, 'productFromMaster']);
	Route::post('productfrommain',[App\Http\Controllers\Api\ProductCategoryController::class, 'productFromMain']);
	Route::post('productfromcategory',[App\Http\Controllers\Api\ProductCategoryController::class, 'productFromCategory']);
	Route::post('productfromsubcategory',[App\Http\Controllers\Api\ProductCategoryController::class, 'productFromSubCategory']);

	//Without Limit master_main_category_subcategory_ product routes 
	Route::post('productfrommasterwithout',[App\Http\Controllers\Api\ProductCategoryController::class, 'productFromMasterWithoutLimit']);
	Route::post('productfrommainwithout',[App\Http\Controllers\Api\ProductCategoryController::class, 'productFromMainWithoutLimit']);
	Route::post('productfromcategorywithout',[App\Http\Controllers\Api\ProductCategoryController::class, 'productFromCategoryWithoutLimit']);
	Route::post('productfromsubcategorywithout',[App\Http\Controllers\Api\ProductCategoryController::class, 'productFromSubCategoryWithoutLimit']);

	//Cart route
	Route::post('addtocart',[App\Http\Controllers\Api\CartController::class, 'addToCart']);
	Route::post('showcart',[App\Http\Controllers\Api\CartController::class, 'showCart']);
	Route::get('usercart',[App\Http\Controllers\Api\CartController::class, 'userCart']);
  
	//checkout route
	Route::post('checkout',[App\Http\Controllers\Api\CheckOutController::class, 'checkout']);
	
	//Explore route
	Route::get('exploreproducts',[App\Http\Controllers\Api\ExploreController::class, 'exploreProduct']);
	
	//product route
	Route::post('productdetail',[App\Http\Controllers\Api\ProductController::class, 'productDetail']);
	//Search routes
	Route::post('search',[App\Http\Controllers\Api\SearchController::class, 'searchData']);

	//User routes
	Route::post('editprofile',[App\Http\Controllers\Api\UserProfileController::class, 'editUserProfile']);
	Route::post('editmobilenumber',[App\Http\Controllers\Api\UserProfileController::class, 'editMobileNumber']);

	//Banner Category Product route
	Route::post('bannercategoryproduct',[App\Http\Controllers\Api\BannerCategoryProductController::class, 'bannerCategoryProduct']);
	
	//logout route
	Route::post('logout',[App\Http\Controllers\Api\LogoutController::class, 'logout']);

	//Buy It Again route
	Route::get('buyitagain',[App\Http\Controllers\Api\BuyItAgainController::class, 'buyItAgain']);


});