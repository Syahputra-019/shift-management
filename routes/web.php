<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/jadwal-shift', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/swap-shift', [DashboardController::class, 'swapShiftPage'])->name('swap-shift.index');
    Route::post('/swap-requests/{id}/respond', [DashboardController::class, 'respondSwapRequest'])->name('swap-requests.respond');
    Route::post('/swap-requests', [DashboardController::class, 'submitSwapRequest'])->name('swap-requests.store');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

require __DIR__.'/auth.php';
