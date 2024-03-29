<?php

use App\Http\Controllers\Administration\Invoice\PDFBuilderController;
use App\Http\Controllers\Administration\Report\ReportController;
use App\Http\Controllers\Commercial\BCommand\BCommandController;
use App\Http\Controllers\Commercial\Bill\BillController;
use App\Http\Controllers\Commercial\Company\CompanyController;
use App\Http\Controllers\Commercial\Estimate\EstimateController;
use App\Http\Controllers\Commercial\Invoice\InvoiceController;
use App\Http\Controllers\Commercial\InvoiceAvoir\InvoiceAvoirController;
use App\Http\Controllers\Commercial\Provider\ProviderController;
use Illuminate\Support\Facades\Route;

Route::middleware('cache.headers:public;max_age=2628000;etag')->group(function () {
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
});

Route::group(['prefix' => 'invoices', 'middleware' => 'role_or_permission:SuperAdmin|invoices.browse'], function () {
    Route::get('/', [InvoiceController::class, 'indexFilter'])->name('invoices.index');

    Route::get('/create', [InvoiceController::class, 'create'])->can('invoices.create')->name('invoices.create');
    Route::post('/create', [InvoiceController::class, 'store'])->can('invoices.create')->name('invoices.store');
    Route::delete('/', [InvoiceController::class, 'deleteInvoice'])->can('invoices.delete')->name('invoices.delete');

    Route::post('/send', [InvoiceController::class, 'sendInvoice'])->name('invoices.send');

    Route::group(['prefix' => 'overview/invoice'], function () {
        Route::get('/{invoice}', [InvoiceController::class, 'single'])->name('invoices.single');
    });

    Route::group(['prefix' => 'edit/invoice'], function () {
        Route::get('/{invoice}', [InvoiceController::class, 'edit'])->can('invoices.edit')->name('invoices.edit');
        Route::post('/{invoice}', [InvoiceController::class, 'update'])->can('invoices.edit')->name('invoices.update');
        Route::delete('/delete', [InvoiceController::class, 'deleteArticle'])->can('invoices.delete')->name('invoices.delete.article');
    });

    Route::group(['prefix' => 'PDF/invoice'], function () {
        Route::get('/{invoice}', [PDFBuilderController::class, 'build'])->name('invoices.pdf.build');
    });

    Route::group(['prefix' => 'invoices-avoir'], function () {
        Route::get('/', [InvoiceAvoirController::class, 'index'])->name('invoices.index.avoir');
        Route::get('/create', [InvoiceAvoirController::class, 'create'])->can('invoices.create')->name('invoices.create.avoir');
        Route::post('/create', [InvoiceAvoirController::class, 'store'])->can('invoices.create')->name('invoices.store.avoir');
        Route::delete('/', [InvoiceAvoirController::class, 'deleteInvoice'])->can('invoices.delete')->name('invoices.delete.avoir');

        Route::post('/send', [InvoiceAvoirController::class, 'sendInvoiceAvoir'])->name('invoices.send.avoir');

        Route::group(['prefix' => 'overview/invoice'], function () {
            Route::get('/{invoice}', [InvoiceAvoirController::class, 'single'])->name('invoices.single.avoir');
        });

        Route::group(['prefix' => 'edit/invoices-avoir'], function () {
            Route::get('/{invoice}', [InvoiceAvoirController::class, 'edit'])->can('invoices.edit')->name('invoices.edit.avoir');
            Route::post('/{invoice}', [InvoiceAvoirController::class, 'update'])->can('invoices.edit')->name('invoices.update.avoir');
            Route::delete('/delete', [InvoiceAvoirController::class, 'deleteArticle'])->can('invoices.delete')->name('invoices.delete.article.avoir');
        });

        Route::group(['prefix' => 'PDF/invoices-avoir'], function () {
            Route::get('/{invoice}', [PDFBuilderController::class, 'buildAvoir'])->name('invoices.pdf.build.avoir');
        });
    });
});

Route::group(['prefix' => 'bills'], function () {
    Route::get('/', [BillController::class, 'index'])->name('bills.index');
    Route::post('/', [BillController::class, 'store'])->name('bills.store.normal');

    Route::delete('/delete', [BillController::class, 'deleteBill'])->name('bills.delete');

    Route::group(['prefix' => 'bill/invoice'], function () {
        Route::get('/{invoice}', [BillController::class, 'addBill'])->name('bills.addBill');
        Route::post('/{invoice}', [BillController::class, 'storeBill'])->name('bills.storeBill');
    });

    Route::group(['prefix' => 'bill/invoice-avoir'], function () {
        Route::get('/{invoice}', [BillController::class, 'addBillAvoir'])->name('bills.addBill.avoir');
        Route::post('/{invoice}', [BillController::class, 'storeBillAvoir'])->name('bills.storeBill.avoir');
        Route::delete('/delete', [BillController::class, 'delete'])->name('bills.delete.avoir');
    });

    Route::group(['prefix' => 'bill/edit'], function () {
        Route::get('/{bill}', [BillController::class, 'edit'])->name('bills.edit');
        Route::post('/{bill}', [BillController::class, 'update'])->name('bills.update');
    });

    Route::group(['prefix' => 'bill/create'], function () {
        Route::get('/', [BillController::class, 'create'])->name('bills.create');
        Route::post('/', [BillController::class, 'storeBill'])->name('bills.store');
    });
});

Route::group(['prefix' => 'estimates'], function () {
    Route::get('/', [EstimateController::class, 'indexFilter'])->name('estimates.index');

    Route::get('/create', [EstimateController::class, 'create'])->name('estimates.create');
    Route::post('/create', [EstimateController::class, 'store'])->name('estimates.store');
    Route::delete('/', [EstimateController::class, 'deleteEstimate'])->name('estimates.delete');

    Route::post('/send', [EstimateController::class, 'sendEstimate'])->name('estimates.send');

    Route::group(['prefix' => 'overview/estimate'], function () {
        Route::get('/{estimate}', [EstimateController::class, 'single'])->name('estimates.single');
    });

    Route::group(['prefix' => 'edit/estimate'], function () {
        Route::get('/{estimate}', [EstimateController::class, 'edit'])->name('estimates.edit');
        Route::post('/{estimate}', [EstimateController::class, 'update'])->name('estimates.update');
        Route::delete('/delete', [EstimateController::class, 'deleteArticle'])->name('estimates.delete.article');

        Route::put('/articles', [EstimateController::class, 'updateArticle'])->name('estimates.update.article');
    });

    Route::group(['prefix' => 'estimate/create-invoice'], function () {
        Route::get('/{estimate}', [EstimateController::class, 'createInvoice'])->name('estimates.create.invoice');
    });

    Route::group(['prefix' => 'estimate/create-from-ticket'], function () {
        Route::get('/{ticket}', [EstimateController::class, 'createFromTicket'])->name('estimates.create.ticket');
    });
});

Route::middleware('cache.headers:public;max_age=2628000;etag')->group(function () {
    Route::group(['prefix' => 'providers'], function () {
        Route::get('/', [ProviderController::class, 'index'])->name('providers.index');
        Route::get('/create', [ProviderController::class, 'create'])->name('providers.create');
        Route::post('/create', [ProviderController::class, 'store'])->name('providers.createPost');
        Route::delete('/', [ProviderController::class, 'delete'])->name('providers.delete');

        Route::group(['prefix' => 'edit/provider'], function () {
            Route::get('/{provider}', [ProviderController::class, 'edit'])->name('providers.edit');
            Route::post('/{provider}', [ProviderController::class, 'update'])->name('providers.update');
            Route::post('/{provider}/emails', [ProviderController::class, 'addEmails'])->name('providers.add.emails');
            Route::post('/{provider}/phones', [ProviderController::class, 'addPhones'])->name('providers.add.phones');

            Route::delete('/delete-phone', [ProviderController::class, 'deletePhone'])->name('providers.delete.phone');
            Route::delete('/delete-email', [ProviderController::class, 'deleteEmail'])->name('providers.delete.email');
        });
    });

    Route::group(['prefix' => 'bons-commands'], function () {
        Route::get('/', [BCommandController::class, 'indexFilter'])->name('bcommandes.index');
        Route::get('/create', [BCommandController::class, 'create'])->name('bcommandes.create');
        Route::post('/create', [BCommandController::class, 'store'])->name('bcommandes.createPost');
        Route::delete('/', [BCommandController::class, 'deleteCommand'])->name('bcommandes.delete');

        Route::post('/send', [BCommandController::class, 'sendBC'])->name('bcommandes.send');

        Route::group(['prefix' => 'edit/order'], function () {
            Route::get('/{command}', [BCommandController::class, 'edit'])->name('bcommandes.edit');
            Route::post('/{command}', [BCommandController::class, 'update'])->name('bcommandes.update');
            Route::delete('/delete-article', [BCommandController::class, 'deleteArticle'])->name('bcommandes.delete.article');
        });

        Route::group(['prefix' => 'overview/order'], function () {
            Route::get('/{command}', [BCommandController::class, 'single'])->name('bcommandes.single');
        });
    });

    Route::group(['prefix' => 'reports'], function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');

        Route::group(['prefix' => 'overview/report'], function () {
            Route::get('/{client}', [ReportController::class, 'single'])->name('reports.single');
        });
    });
});
