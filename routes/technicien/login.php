<?php


use App\Http\Controllers\Authentification\Technicien\TechnicienLoginController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [TechnicienLoginController::class, 'loginForm'])->name('login');
