<?php

use App\Http\Controllers\Importer\CSVImporterController;
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

Route::get('/post', [SiteController::class, 'index']);

Route::redirect('/', '/app')->name('home');

Route::group(['prefix' => 'views'], function () {

    Route::group(['prefix' => 'invoices'], function () {
        Route::get('/invoice/{invoice}', [PDFPublicController::class, 'showInvoice'])->name('public.show.invoice');
    });
});



Route::get('/upload', [CSVImporterController::class, 'index']);
Route::post('/upload', [CSVImporterController::class, 'upload']);
Route::get('/batch', [CSVImporterController::class, 'batch']);