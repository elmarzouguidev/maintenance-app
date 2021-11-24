<?php

use App\Http\Controllers\Site\SiteController;
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
    return view('welcome');
})->name('login');

Route::get('/tester', [SiteController::class, 'index']);

Route::get('/technicien',[SiteController::class,'admins'])->middleware('auth:technicien')->name('technicien');

Route::get('/admins',[SiteController::class,'dashboard'])->middleware('auth:admin')->name('admins');

Route::group(['middleware' => 'verified'], function () {

    Route::get('/profile', [SiteController::class,'profile'])->name('profile.show');
    Route::get('/settings', [SiteController::class,'settings'])->name('settings.show');

});


Route::group(['middleware' => 'auth'], function () {

    Route::get('/dashboard', [SiteController::class,'profile'])->name('dashboard');
    Route::get('/configs', [SiteController::class,'settings'])->name('configs');

});
