<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        // Laravel's built-in pagination views are Tailwind-styled, but this
        // project uses plain CSS with no Tailwind — so those classes did
        // nothing and pagination rendered as unstyled, oddly-sized raw
        // links/SVGs. Use our own view that matches the actual site CSS.
        Paginator::defaultView('admin.partials.pagination');
        Paginator::defaultSimpleView('admin.partials.pagination');
    }
}
