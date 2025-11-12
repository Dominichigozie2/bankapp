<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;
use App\Models\AdminSetting;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * 🧩 Share recent activities with the user header layout
         */
        View::composer('account.user.layout.header', function ($view) {
            $recentActivities = collect(); // Empty collection by default

            if (Auth::check()) {
                $recentActivities = Activity::where('user_id', Auth::id())
                    ->orderByDesc('created_at')
                    ->limit(5)
                    ->get(['description', 'created_at']); // Fetch only needed columns
            }

            $view->with('recentActivities', $recentActivities);
        });

        /**
         * 🌐 Share Admin Settings globally (logo, admin_email, etc.)
         */
        View::composer('*', function ($view) {
            $settings = AdminSetting::first(); // Get the first (and usually only) row
            $view->with('settings', $settings);
        });
    }
}
