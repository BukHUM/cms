<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use App\Helpers\SettingsHelper;

class TimezoneLocaleServiceProvider extends ServiceProvider
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
        // Set timezone from database settings
        $timezone = SettingsHelper::get('timezone', config('app.timezone'));
        if ($timezone) {
            config(['app.timezone' => $timezone]);
            date_default_timezone_set($timezone);
            Carbon::setTestNow(Carbon::now($timezone));
        }

        // Set locale from database settings
        $locale = SettingsHelper::get('language', config('app.locale'));
        if ($locale) {
            config(['app.locale' => $locale]);
            App::setLocale($locale);
        }
    }
}
