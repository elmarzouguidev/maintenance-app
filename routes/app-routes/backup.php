<?php

use App\Http\Controllers\Administration\Backup\BackupController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'app'], function () {

    Route::get('/', [BackupController::class, 'getFiles'])->name('index');
    Route::post('/download/', [BackupController::class, 'downloadFile'])->name('download');
    Route::get('/db', [BackupController::class, 'backupOnlyDb'])->name('db');
});
