<?php

use App\Http\Controllers\Administration\Admin\AdminController;
use App\Http\Controllers\Administration\Admin\CalendarController;
use App\Http\Controllers\Administration\Admin\ContactController;
use App\Http\Controllers\Administration\Admin\DashboardController;
use App\Http\Controllers\Administration\Admin\ReceptionController;
use App\Http\Controllers\Administration\Admin\TechnicienController;
use App\Http\Controllers\Administration\Ticket\TicketController;
use App\Http\Controllers\Administration\Category\CategoryController;
use App\Http\Controllers\Administration\Chat\ChatController;
use App\Http\Controllers\Administration\Email\EmailController;
use App\Http\Controllers\Administration\Client\ClientController;
use App\Http\Controllers\Administration\Diagnostic\DiagnosticController;
use App\Http\Controllers\Administration\Reparation\ReparationController;
use App\Http\Controllers\Administration\Report\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/admin', [DashboardController::class, 'index'])->name('home');
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');


Route::group(['prefix' => 'admins'], function () {

    Route::get('/', [AdminController::class, 'index'])->name('admins');
    Route::get('/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('/create', [AdminController::class, 'store'])->name('admins.createPost');
    Route::delete('/delete', [AdminController::class, 'delete'])->name('admins.delete');
});

Route::group(['prefix' => 'techniciens'], function () {

    Route::get('/', [TechnicienController::class, 'index'])->name('techniciens.list');
    Route::get('/create', [TechnicienController::class, 'create'])->name('techniciens.create');
    Route::post('/create', [TechnicienController::class, 'store'])->name('techniciens.createPost');
    Route::delete('/delete', [TechnicienController::class, 'delete'])->name('techniciens.delete');
});

Route::group(['prefix' => 'receptions'], function () {

    Route::get('/', [ReceptionController::class, 'index'])->name('receptions.list');
    Route::get('/create', [ReceptionController::class, 'create'])->name('receptions.create');
    Route::post('/create', [ReceptionController::class, 'store'])->name('receptions.createPost');
    Route::delete('/delete', [ReceptionController::class, 'delete'])->name('receptions.delete');
});

Route::group(['prefix' => 'tickets', 'middleware' => 'optimizeImages'], function () {

    Route::get('/', [TicketController::class, 'index'])->name('tickets.list');
    Route::get('/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/create', [TicketController::class, 'store'])->name('tickets.createPost');
    Route::delete('/delete', [TicketController::class, 'delete'])->name('tickets.delete');

    Route::group(['prefix' => 'overview'], function () {

        Route::get('/ticket/{slug}', [TicketController::class, 'show'])->name('tickets.single');
        Route::get('/ticket/edit/{id}', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/ticket/edit/{id}', [TicketController::class, 'update'])->name('tickets.update');
        Route::post('/ticket/edit/{id}', [TicketController::class, 'attachements'])->name('tickets.attachements');
        Route::post('/ticket/download-files', [TicketController::class, 'downloadFiles'])->name('tickets.downloadFiles');
    });

    Route::group(['prefix' => 'diagnose'], function () {

        Route::get('/{slug}', [TicketController::class, 'diagnose'])->name('tickets.diagnose');

        Route::post('/{slug}', [ReportController::class, 'store'])->name('tickets.diagnose');

        Route::post('/send-report/{slug}', [ReportController::class, 'sendReport'])->name('tickets.diagnose.send-report');
        Route::post('/send-confirm/{slug}', [ReportController::class, 'sendConfirm'])->name('tickets.diagnose.send-confirm');
    });
});

Route::group(['prefix' => 'diagnostic'], function () {
    Route::get('/', [DiagnosticController::class, 'index'])->name('diagnostic.index');
});

Route::group(['prefix' => 'reparations'], function () {

    Route::get('/', [ReparationController::class, 'index'])->name('reparations.index');

    Route::group(['prefix' => 'overview'], function () {

        Route::get('/ticket/{slug}', [ReparationController::class, 'single'])->name('reparations.single');
        Route::post('/ticket/{slug}', [ReparationController::class, 'store'])->name('reparations.store');
        Route::post('/ticket/repear-complet/{slug}', [ReparationController::class, 'repearComplet'])->name('reparations.complet');
    });
});

Route::group(['prefix' => 'categories'], function () {

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.createPost');

    Route::group(['prefix' => 'overview'], function () {
    });
});


Route::group(['prefix' => 'discussion'], function () {


    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

    Route::group(['prefix' => 'overview'], function () {
    });
});

Route::group(['prefix' => 'emails'], function () {


    Route::get('/inbox', [EmailController::class, 'index'])->name('emails.inbox');

    Route::group(['prefix' => 'overview'], function () {
        Route::get('/email', [EmailController::class, 'show'])->name('emails.show');
    });
});


Route::group(['prefix' => 'clients'], function () {

    Route::get('/', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/create', [ClientController::class, 'store'])->name('clients.createPost');

    Route::get('/edit/{id}', [ClientController::class, 'edit'])->name('client.edit');
    Route::post('/edit/{id}', [ClientController::class, 'update'])->name('client.update');

    Route::group(['prefix' => 'overview'], function () {

        Route::get('/client/{slug}', [ClientController::class, 'show'])->name('clients.show');
    });
});
