<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Site\CategoryController;
use App\Http\Controllers\Site\ClientController;
use App\Http\Controllers\Site\SiteController;
use App\Http\Controllers\Web\PDFPublicController;
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

Route::group(['prefix' => 'views'], function () {

    Route::group(['prefix' => 'invoices'], function () {
        Route::get('/invoice/{invoice}', PDFPublicController::class, 'showInvoice')->name('public.show.invoice');
    });
});
