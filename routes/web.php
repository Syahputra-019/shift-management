<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/jadwal-shift', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/swap-shift', [DashboardController::class, 'swapShiftPage'])->name('swap-shift.index');
    Route::post('/swap-requests/{id}/respond', [DashboardController::class, 'respondSwapRequest'])->name('swap-requests.respond');
    Route::post('/swap-requests', [DashboardController::class, 'submitSwapRequest'])->name('swap-requests.store');
});

require __DIR__.'/auth.php';
