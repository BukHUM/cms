<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
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
        // Configure pagination view
        Paginator::defaultView('vendor.pagination.tailwind');
        Paginator::defaultSimpleView('vendor.pagination.simple-tailwind');
        
        // Configure route model binding for settings-general routes
        Route::model('settings_general', \App\Models\Setting::class);
        
        // Register Auth Components
        $this->registerAuthComponents();
    }
    
    /**
     * Register Auth Components
     */
    private function registerAuthComponents(): void
    {
        // Register auth components
        $this->loadViewComponentsAs('auth', [
            'email-field' => \App\View\Components\Auth\EmailField::class,
            'password-field' => \App\View\Components\Auth\PasswordField::class,
            'submit-button' => \App\View\Components\Auth\SubmitButton::class,
        ]);
    }
}
