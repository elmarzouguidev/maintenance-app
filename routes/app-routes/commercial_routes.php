<?php

use App\Http\Controllers\Commercial\Company\CompanyController;
use App\Http\Controllers\Commercial\Estimate\EstimateController;
use App\Http\Controllers\Commercial\Invoice\InvoiceController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'companies'], function () {

    Route::get('/', [CompanyController::class, 'index'])->name('companies.index');

    Route::get('/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/create', [CompanyController::class, 'store'])->name('companies.store');

    Route::delete('/', [CompanyController::class, 'destroy'])->name('companies.delete');

    Route::group(['prefix' => 'company'], function () {

        Route::get('/{company}', [CompanyController::class, 'edit'])->name('companies.edit');
        Route::post('/{company}', [CompanyController::class, 'update'])->name('companies.update');
    });
});

Route::group(['prefix' => 'invoices'], function () {

    Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
});

Route::group(['prefix' => 'estimates'], function () {

    Route::get('/', [EstimateController::class, 'index'])->name('estimates.index');
});
