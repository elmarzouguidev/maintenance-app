<?php

use App\Http\Controllers\Developper\DevController;
use Illuminate\Support\Facades\Route;


Route::get('/clear-models',[DevController::class,'truncateModels'])->name('truncateModels');
