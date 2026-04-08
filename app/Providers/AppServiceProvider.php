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
        view()->composer('partials.footer', function ($view) {
            $view->with('footerCategories', \App\Models\Category::all());
        });

        view()->composer(['partials.header', 'partials.mobile-menu'], function ($view) {
            $view->with('mainMenus', \App\Models\Menu::whereNull('parent_id')
                ->with('children')
                ->where('is_active', true)
                ->orderBy('order')
                ->get());
        });
    }
}
