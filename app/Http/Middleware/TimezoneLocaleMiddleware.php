<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\SettingsHelper;
use Carbon\Carbon;

class TimezoneLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
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
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
