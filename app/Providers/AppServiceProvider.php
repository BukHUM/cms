<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        // Set pagination view to Bootstrap 5 Thai
        Paginator::defaultView('vendor.pagination.bootstrap-5-thai');
        Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-5');
    }
}
