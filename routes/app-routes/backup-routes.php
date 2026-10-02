<?php

use App\Http\Controllers\Administration\Backup\BackupController;
use App\Http\Controllers\Export\ClientExportController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'manager'], function () {
    Route::get('/', [BackupController::class, 'index'])->can('backup.browse')->name('manager.index');
    Route::post('/', [BackupController::class, 'makeBackup'])->can('backup.create')->name('make');
    Route::delete('/', [BackupController::class, 'deleteBackup'])->can('backup.delete')->name('delete');
    Route::post('/download/', [BackupController::class, 'downloadFile'])->can('backup.download')->name('download');

    Route::get('/clients', [ClientExportController::class, 'export'])->can('client.export')->name('excel.clients');
    Route::post('/clients', [ClientExportController::class, 'import'])->can('client.import')->name('excel.clients.import');

    Route::get('/clients/{disk}', [ClientExportController::class, 'storeToDisk'])->can('client.export')->name('excel.clients.disk');
});
