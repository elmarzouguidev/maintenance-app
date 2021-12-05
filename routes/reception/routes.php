<?php

use App\Http\Controllers\Administration\Reception\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/home', [DashboardController::class, 'index'])->name('home');
