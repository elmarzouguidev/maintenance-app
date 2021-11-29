<?php


use App\Http\Controllers\Authentification\Admin\AdminLoginController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AdminLoginController::class, 'loginForm'])->name('login');
Route::post('/login', [AdminLoginController::class, 'login'])->name('loginPost');
