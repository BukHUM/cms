<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Services\SettingsService;

class BladeDirectivesServiceProvider extends ServiceProvider
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
        // @setting directive
        Blade::directive('setting', function ($expression) {
            return "<?php echo setting($expression); ?>";
        });
        
        // @ifsetting directive
        Blade::directive('ifsetting', function ($expression) {
            return "<?php if(setting($expression)): ?>";
        });
        
        // @ifnotsetting directive
        Blade::directive('ifnotsetting', function ($expression) {
            return "<?php if(!setting($expression)): ?>";
        });
        
        // @endsetting directive
        Blade::directive('endsetting', function ($expression) {
            return "<?php endif; ?>";
        });
        
        // @setting directive with default value
        Blade::directive('setting', function ($expression) {
            return "<?php echo setting($expression); ?>";
        });
    }
}
