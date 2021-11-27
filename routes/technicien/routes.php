<?php


use App\Http\Controllers\Administration\Technicien\DashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/home',[DashboardController::class,'index'])->name('home');
