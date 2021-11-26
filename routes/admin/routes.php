<?php


use App\Http\Controllers\Administration\Admin\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/admin',[DashboardController::class,'index'])->name('home');
