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



Route::get('dashboard',[App\Http\Controllers\LoginAuthController::class, 'dashboard']); 
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


