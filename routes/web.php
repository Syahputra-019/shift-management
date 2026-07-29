<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified']) // Melindungi dashboard
    ->name('dashboard');

require __DIR__.'/auth.php';
