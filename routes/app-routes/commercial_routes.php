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

    Route::get('/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/create', [InvoiceController::class, 'store'])->name('invoices.store');

    Route::group(['prefix' => 'overview/invoice'], function () {

        Route::get('/{invoice}', [InvoiceController::class, 'single'])->name('invoices.single');
    });

    Route::group(['prefix' => 'edit/invoice'], function () {

        Route::get('/{invoice}', [InvoiceController::class, 'edit'])->name('invoices.edit');
        Route::post('/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
        
    });
});

Route::group(['prefix' => 'estimates'], function () {

    Route::get('/', [EstimateController::class, 'index'])->name('estimates.index');
});