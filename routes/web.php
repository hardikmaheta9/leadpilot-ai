<?php

use App\Http\Controllers\CompanyContactController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyNoteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('companies', CompanyController::class)
        ->parameters([
            'companies' => 'uuid',
        ]);

    Route::post('/companies/{companyUuid}/notes', [CompanyNoteController::class, 'store'])
        ->name('companies.notes.store');

    Route::post('/companies/{companyUuid}/contacts', [CompanyContactController::class, 'store'])
        ->name('companies.contacts.store');

    Route::put('/companies/{companyUuid}/contacts/{contactUuid}', [CompanyContactController::class, 'update'])
        ->name('companies.contacts.update');

    Route::delete('/companies/{companyUuid}/contacts/{contactUuid}', [CompanyContactController::class, 'destroy'])
        ->name('companies.contacts.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';