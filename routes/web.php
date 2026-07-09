<?php

use App\Http\Controllers\CompanyContactController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyNoteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CompanyTaskController;
use App\Http\Controllers\CompanyDocumentController;
use App\Http\Controllers\CompanyMeetingController;
use App\Http\Controllers\CompanyCallLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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

    Route::patch('/companies/{companyUuid}/contacts/{contactUuid}/primary', [CompanyContactController::class, 'makePrimary'])
    ->name('companies.contacts.primary');

    Route::get('/search', [SearchController::class, 'index'])
    ->name('search');

    Route::post('/companies/{companyUuid}/tasks', [CompanyTaskController::class, 'store'])
    ->name('companies.tasks.store');
   
   Route::put('/companies/{companyUuid}/tasks/{taskUuid}', [CompanyTaskController::class, 'update'])
    ->name('companies.tasks.update');

   Route::patch('/companies/{companyUuid}/tasks/{taskUuid}/complete', [CompanyTaskController::class, 'complete'])
    ->name('companies.tasks.complete');

   Route::delete('/companies/{companyUuid}/tasks/{taskUuid}', [CompanyTaskController::class, 'destroy'])
    ->name('companies.tasks.destroy');

   Route::post('/companies/{companyUuid}/documents', [CompanyDocumentController::class, 'store'])
    ->name('companies.documents.store');

   Route::delete('/companies/{companyUuid}/documents/{documentUuid}', [CompanyDocumentController::class, 'destroy'])
    ->name('companies.documents.destroy');


   Route::post('/companies/{companyUuid}/meetings', [CompanyMeetingController::class, 'store'])
    ->name('companies.meetings.store');

  Route::put('/companies/{companyUuid}/meetings/{meetingUuid}', [CompanyMeetingController::class, 'update'])
    ->name('companies.meetings.update');

  Route::patch('/companies/{companyUuid}/meetings/{meetingUuid}/complete', [CompanyMeetingController::class, 'complete'])
    ->name('companies.meetings.complete');

  Route::delete('/companies/{companyUuid}/meetings/{meetingUuid}', [CompanyMeetingController::class, 'destroy'])
    ->name('companies.meetings.destroy');

  Route::post('/companies/{companyUuid}/calls', [CompanyCallLogController::class, 'store'])
    ->name('companies.calls.store');

  Route::put('/companies/{companyUuid}/calls/{callUuid}', [CompanyCallLogController::class, 'update'])
    ->name('companies.calls.update');

  Route::delete('/companies/{companyUuid}/calls/{callUuid}', [CompanyCallLogController::class, 'destroy'])
    ->name('companies.calls.destroy');
    
   

});

require __DIR__.'/auth.php';