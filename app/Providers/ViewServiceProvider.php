<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('account.user.layout.header', function ($view) {
            $recentActivities = [];

            if (Auth::check()) {
                $recentActivities = Activity::where('user_id', Auth::id())
                    ->latest()
                    ->take(5)
                    ->get();
            }

            $view->with('recentActivities', $recentActivities);
        });
    }
}
