<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Site\CategoryController;
use App\Http\Controllers\Site\ClientController;
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
});

Route::get('/tester', [SiteController::class, 'index'])->name('home');

Route::get('/helpers', [SiteController::class, 'helpers'])->name('helpers');

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

Route::get('/users',[AdminController::class,'index']);


Route::get('/clients/create',[ClientController::class,'index']);

Route::post('/clients/create',[ClientController::class,'create'])->name('clients.add');

Route::get('/category/create',[CategoryController::class,'index']);

Route::post('/category/create',[CategoryController::class,'create'])->name('category.add');


Route::get('/admin/create',[\App\Http\Controllers\Site\AdminController::class,'index']);
Route::post('/admin/create',[\App\Http\Controllers\Site\AdminController::class,'create'])->name('admin.add');
