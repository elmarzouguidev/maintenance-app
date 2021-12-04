<?php

use App\Http\Controllers\Administration\Admin\Permission\PermissionsController;
use Illuminate\Support\Facades\Route;

Route::get('/create', [PermissionsController::class, 'create'])->name('create');
