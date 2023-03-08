<?php

use App\Http\Controllers\Authentification\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('cache.headers:public;max_age=2628000;etag')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('loginPost');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
