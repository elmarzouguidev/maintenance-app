<?php


use App\Http\Controllers\Administration\Admin\Role\RolesController;
use Illuminate\Support\Facades\Route;

Route::create('/create', [RolesController::class, 'create'])->name('create');
