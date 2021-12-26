<?php

use App\Http\Controllers\Administration\Admin\AdminController;
use App\Http\Controllers\Administration\Admin\CalendarController;
use App\Http\Controllers\Administration\Admin\ContactController;
use App\Http\Controllers\Administration\Admin\DashboardController;
use App\Http\Controllers\Administration\Admin\TechnicienController;
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

Route::group(['prefix' => 'techniciens'], function () {

    Route::get('/', [TechnicienController::class, 'index'])->name('techniciens.list');
    Route::get('/create', [TechnicienController::class, 'create'])->name('techniciens.create');
    Route::post('/create', [TechnicienController::class, 'store'])->name('techniciens.createPost');
    Route::delete('/delete', [TechnicienController::class, 'delete'])->name('techniciens.delete');
});
