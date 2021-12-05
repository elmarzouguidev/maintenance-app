<?php

use App\Http\Controllers\Authentification\Reception\ReceptionLoginController;

use Illuminate\Support\Facades\Route;


Route::get('/login', [ReceptionLoginController::class, 'loginForm'])->name('login');
Route::post('/login', [ReceptionLoginController::class, 'login'])->name('loginPost');

Route::get('/logout', [ReceptionLoginController::class, 'logout'])->name('logout');
