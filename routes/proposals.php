<?php

use App\Http\Controllers\AIProposalController;
use App\Http\Controllers\PublicProposalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('companies/{company}/proposal')
    ->name('companies.proposal.')
    ->group(function () {
        Route::post('/generate', [AIProposalController::class, 'generate'])->name('generate');
        Route::get('/latest', [AIProposalController::class, 'latest'])->name('latest');
        Route::get('/history', [AIProposalController::class, 'history'])->name('history');
        Route::get('/analytics', [AIProposalController::class, 'analytics'])->name('analytics');
        Route::get('/{proposal}/pdf', [AIProposalController::class, 'pdf'])->name('pdf');
        Route::get('/{proposal}/docx', [AIProposalController::class, 'docx'])->name('docx');
        Route::get('/{proposal}/send', [AIProposalController::class, 'compose'])->name('send');
        Route::post('/{proposal}/send', [AIProposalController::class, 'send'])->name('send.store');
        Route::get('/{proposal}', [AIProposalController::class, 'show'])->name('show');
    });

Route::prefix('p/{token}')
    ->name('proposals.public.')
    ->group(function () {
        Route::get('/', [PublicProposalController::class, 'show'])->name('show');
        Route::get('/download', [PublicProposalController::class, 'download'])->name('download');
        Route::post('/respond', [PublicProposalController::class, 'respond'])
            ->middleware('throttle:10,1')
            ->name('respond');
    });
