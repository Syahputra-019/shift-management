<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (auth()->check()) {
                try {
                    $pendingSwapCount = \App\Models\ShiftSwapRequest::where('status', 'pending')->count();
                    $view->with('pendingSwapCount', $pendingSwapCount);
                } catch (\Exception $e) {
                    $view->with('pendingSwapCount', 0);
                }
            }
        });
    }
}
