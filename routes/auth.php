<?php

/*
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
*/
use Illuminate\Support\Facades\Route;

// CATATAN: Rute berbasis Controller diganti dengan closure (fungsi placeholder)
// karena file-file controller otentikasi tidak ditemukan di proyek Anda.
// Ini memungkinkan server untuk berjalan, tetapi fungsionalitas otentikasi tidak akan bekerja.
//
// Untuk memperbaikinya secara permanen, jalankan perintah berikut di terminal Anda:
// 1. composer require laravel/breeze --dev
// 2. php artisan breeze:install
//
// Setelah itu, Anda bisa mengembalikan isi file ini seperti semula untuk menggunakan Controller yang asli.

Route::middleware('guest')->group(function () {
    Route::get('register', function () {
        return 'Placeholder untuk form registrasi. Jalankan `php artisan breeze:install`.';
    })->name('register');

    Route::post('register', function () {
        //
    });

    Route::get('login', function () {
        return 'Placeholder untuk form login. Jalankan `php artisan breeze:install`.';
    })->name('login');

    Route::post('login', function () {
        //
    });

    Route::get('forgot-password', function () {
        return 'Placeholder untuk lupa password. Jalankan `php artisan breeze:install`.';
    })->name('password.request');

    Route::post('forgot-password', function () {
        //
    })->name('password.email');

    Route::get('reset-password/{token}', function ($token) {
        return 'Placeholder untuk reset password. Jalankan `php artisan breeze:install`.';
    })->name('password.reset');

    Route::post('reset-password', function () {
        //
    })->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', function () {
        return 'Placeholder untuk verifikasi email. Jalankan `php artisan breeze:install`.';
    })->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', function ($id, $hash) {
        //
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('email/verification-notification', function () {
        //
    })->middleware('throttle:6,1')->name('verification.send');

    Route::get('confirm-password', function () {
        //
    })->name('password.confirm');

    Route::post('confirm-password', function () {
        //
    });

    Route::put('password', function () {
        //
    })->name('password.update');

    Route::post('logout', function () {
        //
    })->name('logout');
});