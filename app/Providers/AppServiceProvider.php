<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

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
         View::composer('partials.header', function ($view) {
        $recentActivities = collect();
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
