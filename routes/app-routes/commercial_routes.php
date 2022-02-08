<?php

use App\Http\Controllers\Administration\Document\DocumentController;
use App\Http\Controllers\Administration\Invoice\PDFBuilderController;
use App\Http\Controllers\Commercial\BCommand\BCommandController;
use App\Http\Controllers\Commercial\Bill\BillController;
use App\Http\Controllers\Commercial\Company\CompanyController;
use App\Http\Controllers\Commercial\Estimate\EstimateController;
use App\Http\Controllers\Commercial\Invoice\InvoiceController;
use App\Http\Controllers\Commercial\Provider\ProviderController;
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

    Route::get('/', [InvoiceController::class, 'indexFilter'])->name('invoices.index');

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

    Route::group(['prefix' => 'PDF/invoice'], function () {

        Route::get('/{invoice}', [PDFBuilderController::class, 'build'])->name('invoices.pdf.build');
    });
});

Route::group(['prefix' => 'bills'], function () {

    Route::get('/', [BillController::class, 'index'])->name('bills.index');

    Route::group(['prefix' => 'bill/invoice'], function () {

        Route::get('/{invoice}', [BillController::class, 'addBill'])->name('bills.addBill');
        Route::post('/{invoice}', [BillController::class, 'storeBill'])->name('bills.storeBill');
        Route::delete('/delete', [BillController::class, 'delete'])->name('bills.delete');
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

Route::group(['prefix' => 'documents'], function () {

    Route::prefix('BL')->group(function () {
        Route::get('/', [DocumentController::class, 'bl'])->name('documents.bl');
    });

    Route::prefix('BC')->group(function () {
        Route::get('/', [DocumentController::class, 'bc'])->name('documents.bc');
    });

    Route::post('/', [DocumentController::class, 'createDoc'])->name('documents.create');
});


Route::group(['prefix' => 'providers'], function () {

    Route::get('/', [ProviderController::class, 'index'])->name('providers.index');
    Route::get('/create', [ProviderController::class, 'create'])->name('providers.create');
    Route::post('/create', [ProviderController::class, 'store'])->name('providers.createPost');
    Route::delete('/', [ProviderController::class, 'delete'])->name('providers.delete');

    Route::group(['prefix' => 'edit/provider'], function () {

        Route::get('/{provider}', [ProviderController::class, 'edit'])->name('providers.edit');
        Route::post('/{provider}', [ProviderController::class, 'update'])->name('providers.update');
    });
});

Route::group(['prefix' => 'purchase-order'], function () {

    Route::get('/', [BCommandController::class, 'index'])->name('bcommandes.index');
    Route::get('/create', [BCommandController::class, 'create'])->name('bcommandes.create');
    Route::post('/create', [BCommandController::class, 'store'])->name('bcommandes.createPost');
    Route::delete('/', [BCommandController::class, 'deleteCommand'])->name('bcommandes.delete');

    Route::group(['prefix' => 'edit/order'], function () {

        Route::get('/{command}', [BCommandController::class, 'edit'])->name('bcommandes.edit');
        Route::post('/{command}', [BCommandController::class, 'update'])->name('bcommandes.update');
        Route::delete('/delete-article', [BCommandController::class, 'deleteArticle'])->name('bcommandes.delete.article');
    });

    Route::group(['prefix' => 'overview/order'], function () {

        Route::get('/{command}', [BCommandController::class, 'single'])->name('bcommandes.single');
    });
});
