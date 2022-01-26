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
    Route::delete('/', [InvoiceController::class, 'deleteInvoice'])->name('invoices.delete');


    Route::group(['prefix' => 'overview/invoice'], function () {

        Route::get('/{invoice}', [InvoiceController::class, 'single'])->name('invoices.single');
    });

    Route::group(['prefix' => 'edit/invoice'], function () {

        Route::get('/{invoice}', [InvoiceController::class, 'edit'])->name('invoices.edit');
        Route::post('/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
        Route::delete('/delete', [InvoiceController::class, 'deleteArticle'])->name('invoices.delete.article');
    });
});

Route::group(['prefix' => 'estimates'], function () {

    Route::get('/', [EstimateController::class, 'index'])->name('estimates.index');

    Route::get('/create', [EstimateController::class, 'create'])->name('estimates.create');
    Route::post('/create', [EstimateController::class, 'store'])->name('estimates.store');
    Route::delete('/', [EstimateController::class, 'deleteEstimate'])->name('estimates.delete');


    Route::group(['prefix' => 'overview/estimate'], function () {

        Route::get('/{estimate}', [EstimateController::class, 'single'])->name('estimates.single');
    });

    Route::group(['prefix' => 'edit/estimate'], function () {

        Route::get('/{estimate}', [EstimateController::class, 'edit'])->name('estimates.edit');
        Route::post('/{estimate}', [EstimateController::class, 'update'])->name('estimates.update');
        Route::delete('/delete', [EstimateController::class, 'deleteArticle'])->name('estimates.delete.article');
    });

    Route::group(['prefix' => 'estimate/create-invoice'], function () {

        Route::get('/{estimate}', [EstimateController::class, 'createInvoice'])->name('estimates.create.invoice');
    });
});
