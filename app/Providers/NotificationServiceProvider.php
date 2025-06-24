<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Bagikan data notifikasi ke semua view yang menggunakan layout 'layouts.navigation'
        View::composer('layouts.navigation', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $notifications = $user->unreadNotifications()->take(5)->get();
                $unreadCount = $user->unreadNotifications()->count();
                $view->with(['notifications' => $notifications, 'unreadCount' => $unreadCount]);
            }
        });
    }
}
