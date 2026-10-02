<?php

use App\Http\Controllers\Administration\Admin\AdminController;
use App\Http\Controllers\Administration\Admin\CalendarController;
use App\Http\Controllers\Administration\Admin\ContactController;
use App\Http\Controllers\Administration\Admin\DashboardController;
use App\Http\Controllers\Administration\Category\CategoryController;
use App\Http\Controllers\Administration\Client\ClientController;
use App\Http\Controllers\Administration\Client\ImportClientController;
use App\Http\Controllers\Administration\Diagnostique\DiagnostiqueController;
use App\Http\Controllers\Administration\Email\EmailController;
use App\Http\Controllers\Administration\Import\CSVImportController;
use App\Http\Controllers\Administration\PDF\GenerateReportController;
use App\Http\Controllers\Administration\PermissionRole\PermissionRoleController;
use App\Http\Controllers\Administration\Profil\ProfilController;
use App\Http\Controllers\Administration\Reparation\ReparationController;
use App\Http\Controllers\Administration\Setting\SettingController;
use App\Http\Controllers\Administration\Ticket\TicketController;
use App\Http\Controllers\Administration\Warranty\WarrantyController;
use App\Http\Controllers\Rapport\RapportController;
use Illuminate\Support\Facades\Route;

Route::get('/admin', [DashboardController::class, 'index'])->name('home');
Route::get('/admin/tickets-livrable', [DashboardController::class, 'ticketLivrable'])
    ->can('ticket.delivery.browse')->name('tickets.livrable');
Route::post('/admin/tickets-livrable', [DashboardController::class, 'confirmLivrable'])
    ->can('ticket.delivery.confirm')->name('tickets.livrablePost');
Route::post('/admin/tickets-livrable-admin', [DashboardController::class, 'confirmLivrableAdmin'])
    ->can('ticket.delivery.admin_confirm')->name('tickets.livrablePostAdmin');
Route::get('/admin/tickets-invoiceable', [DashboardController::class, 'invoiceable2'])->name('tickets.invoiceable');

Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');

Route::group(['prefix' => 'auth'], function () {
    Route::group(['prefix' => 'admins'], function () {
        Route::get('/', [AdminController::class, 'index'])->can('admin.browse')->name('admins');
        Route::get('/create', [AdminController::class, 'create'])->can('admin.create')->name('admins.create');
        Route::post('/create', [AdminController::class, 'store'])->can('admin.create')->name('admins.createPost');
        Route::delete('/delete', [AdminController::class, 'delete'])->can('admin.delete')->name('admins.delete');

        Route::get('/edit/{admin}', [AdminController::class, 'edit'])->can('admin.edit')->name('admins.edit');
        Route::post('/edit/{admin}', [AdminController::class, 'update'])->can('admin.edit')->name('admins.update');

        // Route::get('/edit/permissions/{admin}', [AdminController::class, 'edit'])->name('admins.edit');
        Route::put('/edit/permissions/{admin}', [AdminController::class, 'syncPermission'])->can('admin.edit')->name('admins.syncPermissions');
    });
});

Route::group(['prefix' => 'tickets'], function () {
    Route::get('/', [TicketController::class, 'index'])->can('ticket.browse')->name('tickets.list');
    Route::get('/create', [TicketController::class, 'create'])->can('ticket.create')->name('tickets.create');
    Route::post('/create', [TicketController::class, 'store'])->can('ticket.create')->name('tickets.createPost');
    Route::delete('/delete', [TicketController::class, 'delete'])->can('ticket.delete')->name('tickets.delete');

    Route::group(['prefix' => 'overview'], function () {
        Route::get('/ticket/{ticket}', [TicketController::class, 'show'])->can('ticket.browse')->name('tickets.single');

        Route::get('/ticket/edit/{ticket}', [TicketController::class, 'edit'])->can('ticket.edit')->name('tickets.edit');
        Route::put('/ticket/edit/{ticket}', [TicketController::class, 'update'])->can('ticket.edit')->name('tickets.update');

        Route::post('/ticket/edit/{ticket}', [TicketController::class, 'attachements'])->can('ticket.edit')->name('tickets.attachements');
        Route::post('/ticket/download-files', [TicketController::class, 'downloadFiles'])->can('ticket.read')->name('tickets.downloadFiles');

        // Super Admin ticket reassignment
        Route::post('/ticket/reassign/{ticket}', [TicketController::class, 'reassign'])->can('ticket.reassign')->name('tickets.reassign');
    });

    Route::group(['prefix' => 'diagnose'], function () {
        Route::get('/{ticket}', [DiagnostiqueController::class, 'diagnose'])
            ->can('diagnostic.browse')->name('tickets.diagnose');
        Route::post('/{ticket}', [DiagnostiqueController::class, 'storeDiagnose'])
            ->can('diagnostic.edit')->name('tickets.diagnose.store');

        Route::post('/send-report/{ticket}', [DiagnostiqueController::class, 'sendReport'])
            ->can('diagnostic.send_report')->name('tickets.diagnose.send-report');
        Route::post('/send-confirm/{ticket}', [DiagnostiqueController::class, 'sendConfirm'])
            ->can('diagnostic.confirm')->name('tickets.diagnose.send-confirm');
    });

    Route::group(['prefix' => 'media'], function () {
        Route::get('/{ticket}', [TicketController::class, 'media'])->can('ticket.read')->name('tickets.media');
        Route::delete('/{ticket}', [TicketController::class, 'deleteMedia'])->can('ticket.edit')->name('tickets.media.delete');
    });

    Route::group(['prefix' => 'historiques'], function () {
        Route::get('/{uuid}', [TicketController::class, 'historical'])->can('ticket.read')->name('tickets.historical');
    });

    Route::group(['prefix' => 'old'], function () {
        Route::get('/', [TicketController::class, 'oldTow'])->can('ticket.browse')->name('tickets.list.old');
    });

    Route::group(['prefix' => 'PDF_/ticket'], function () {
        Route::get('/{ticket}', [GenerateReportController::class, 'ticketReport'])->can('ticket.read')->name('tickets.report.generate');
    });

    Route::post('/', [TicketController::class, 'ticketSettings'])->can('ticket.edit')->name('tickets.settings');
});

Route::group(['prefix' => 'diagnostic'], function () {
    Route::get('/', [DiagnostiqueController::class, 'index'])
        ->can('diagnostic.browse')->name('diagnostic.index');
});

Route::group(['prefix' => 'reparations'], function () {
    Route::get('/', [ReparationController::class, 'index'])
        ->can('reparations.browse')->name('reparations.index');

    Route::group(['prefix' => 'overview'], function () {
        Route::get('/ticket/{ticket}', [ReparationController::class, 'single'])
            ->can('reparations.browse')->name('reparations.single');
        Route::post('/ticket/{ticket}', [ReparationController::class, 'store'])
            ->can('reparations.edit')->name('reparations.store');
        Route::post('/ticket/repear-complet/{ticket}', [ReparationController::class, 'repearComplet'])
            ->can('reparations.complete')->name('reparations.complet');
    });
});

Route::group(['prefix' => 'rapports'], function () {
    Route::get('/', [RapportController::class, 'index'])->can('report.browse')->name('rapports.index');

    Route::get('/editions/', [RapportController::class, 'editions'])->can('report.edit')->name('rapports.editions.index');
    Route::get('/edit/{ticket}', [RapportController::class, 'edit'])->can('report.edit')->name('rapports.editions.edit');
    Route::put('/edit/{ticket}', [RapportController::class, 'update'])->can('report.edit')->name('rapports.editions.update');

    Route::group(['prefix' => 'PDF_'], function () {
        Route::get('/{ticket}', [RapportController::class, 'generateRepport'])->can('report.browse')->name('rapports.report.generate');
    });
});

Route::group(['prefix' => 'categories'], function () {
    Route::get('/categories', [CategoryController::class, 'index'])->can('categories.browse')->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->can('categories.create')->name('categories.store');
    Route::delete('/categories', [CategoryController::class, 'delete'])->can('categories.delete')->name('categories.delete');

    Route::group(['prefix' => 'overview'], function () {});
});

Route::group(['prefix' => 'emails'], function () {
    Route::get('/inbox', [EmailController::class, 'index'])->name('emails.inbox');

    Route::group(['prefix' => 'overview'], function () {
        Route::get('/email', [EmailController::class, 'show'])->name('emails.show');
    });
});

Route::group(['prefix' => 'clients'], function () {
    Route::get('/', [ClientController::class, 'index'])->can('client.browse')->name('clients.index');
    Route::get('/create', [ClientController::class, 'create'])->can('client.create')->name('clients.create');
    Route::post('/create', [ClientController::class, 'store'])->can('client.create')->name('clients.createPost');
    Route::delete('/delete', [ClientController::class, 'delete'])->can('client.delete')->name('clients.delete');

    Route::get('/edit/{client}', [ClientController::class, 'edit'])->can('client.edit')->name('client.edit');
    Route::post('/edit/{client}', [ClientController::class, 'update'])->can('client.edit')->name('client.update');

    Route::post('/edit/{client}/emails', [ClientController::class, 'addEmails'])->can('client.edit')->name('client.add.emails');

    Route::post('/edit/{client}/phones', [ClientController::class, 'addPhones'])->can('client.edit')->name('client.add.phones');

    Route::delete('edit/delete-phone', [ClientController::class, 'deletePhone'])->can('client.delete')->name('client.delete.phone');
    Route::delete('edit/delete-email', [ClientController::class, 'deleteEmail'])->can('client.delete')->name('client.delete.email');

    Route::group(['prefix' => 'overview'], function () {
        Route::get('/client/{client}', [ClientController::class, 'show'])->can('client.browse')->name('clients.show');
    });

    Route::post('/import', [ImportClientController::class, 'import'])->can('client.import')->name('clients.import');
});

Route::group(['prefix' => 'permissions-and-roles'], function () {
    Route::get('/roles', [PermissionRoleController::class, 'index'])->can('roles_permissions.browse')->name('permissions-roles.index');
    Route::post('/roles', [PermissionRoleController::class, 'createRole'])->can('roles_permissions.roles.create')->name('permissions-roles.add');
    Route::delete('/roles', [PermissionRoleController::class, 'deleteRole'])->can('roles_permissions.roles.delete')->name('permissions-roles.delete');

    Route::get('/permissions', [PermissionRoleController::class, 'indexPermission'])->can('roles_permissions.browse')->name('permissions-roles.permissions');
    Route::post('/permissions', [PermissionRoleController::class, 'createPermission'])->can('roles_permissions.permissions.create')->name('permissions-roles.add.permissions');
    Route::delete('/permissions', [PermissionRoleController::class, 'deletePermission'])->can('roles_permissions.permissions.delete')->name('permissions-roles.delete.permissions');
});

Route::group(['prefix' => 'files-importers'], function () {
    Route::get('/csv', [CSVImportController::class, 'index'])->can('imports.csv.browse')->name('files.importers.csv');
});

Route::group(['prefix' => 'profile'], function () {
    Route::get('/', [ProfilController::class, 'index'])->name('profile.index');

    Route::get('/settings', [ProfilController::class, 'settings'])->name('profile.settings');
    Route::post('/settings', [ProfilController::class, 'update'])->name('profile.settings.update');

    Route::post('/settings/company', [ProfilController::class, 'updateCompany'])->name('profile.settings.update.company');
});

Route::group(['prefix' => 'warranties'], function () {
    Route::get('/', [WarrantyController::class, 'index'])->can('warranty.browse')->name('warranty.index');

    Route::get('/create', [WarrantyController::class, 'create'])->can('warranty.create')->name('warranty.create');
    Route::post('/create', [WarrantyController::class, 'store'])->can('warranty.create')->name('warranty.store');
});

Route::group(['prefix' => 'settings'], function () {
    Route::get('/', [SettingController::class, 'index'])->name('settings.index');

    Route::group(['prefix' => 'emails'], function () {
        Route::get('/{email}', [SettingController::class, 'email'])->name('settings.email.single');
    });
});
