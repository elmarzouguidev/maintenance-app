<?php

use App\Http\Controllers\Commercial\Invoice\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'invoices'], function () {

    Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
});
