<?php

use App\Http\Controllers\Administration\Backup\BackupController;
use App\Http\Controllers\Administration\Excel\ExcelController;
use App\Http\Controllers\Export\ClientExportController;
use Illuminate\Support\Facades\Route;


Route::get('/', [BackupController::class, 'getFiles'])->name('index');
Route::post('/', [BackupController::class, 'makeBackup'])->name('make');
Route::delete('/', [BackupController::class, 'deleteBackup'])->name('delete');

Route::post('/download/', [BackupController::class, 'downloadFile'])->name('download');

Route::get('/db', [BackupController::class, 'backupOnlyDb'])->name('db');


Route::group(['prefix' => 'manager'], function () {

    Route::get('/',[BackupController::class, 'getFiles'])->name('excel.index');

    Route::get('/clients', [ClientExportController::class, 'export'])->name('excel.clients');
    Route::post('/clients', [ClientExportController::class, 'import'])->name('excel.clients.import');

    Route::get('/clients/{disk}', [ClientExportController::class, 'storeToDisk'])->name('excel.clients.disk');
});
