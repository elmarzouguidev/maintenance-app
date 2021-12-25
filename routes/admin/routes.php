<?php

use App\Http\Controllers\Administration\Admin\AdminController;
use App\Http\Controllers\Administration\Admin\CalendarController;
use App\Http\Controllers\Administration\Admin\ContactController;
use App\Http\Controllers\Administration\Admin\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/admin', [DashboardController::class, 'index'])->name('home');
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');



Route::group(['prefix' => 'admins'], function () {

    Route::get('/', [AdminController::class, 'index'])->name('admins');
    Route::get('/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('/create', [AdminController::class, 'store'])->name('admins.createPost');
    Route::delete('/delete', [AdminController::class, 'delete'])->name('admins.delete');
    
});
