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

Route::get('/', function () {
    return view('login');
});
Route::get('dashboard',[App\Http\Controllers\LoginAuthController::class, 'dashboard']); 
Route::get('/', [App\Http\Controllers\LoginAuthController::class, 'index'])->name('login');
Route::post('custom-login', [App\Http\Controllers\LoginAuthController::class, 'customLogin'])->name('login.post');
Route::get('signout', [App\Http\Controllers\LoginAuthController::class, 'logOut'])->name('logout');
