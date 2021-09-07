<?php

namespace Iscraper\Providers;

use Illuminate\Support\ServiceProvider;
use Iscraper\Services\LinkedInSearchService;

class IscraperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/iscraper.php' => config_path('iscraper.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../../config/iscraper.php', 'iscraper');

        // Register the main class to use with the facade
        $this->app->singleton('iscraper', function () {
            return new LinkedInSearchService;
        });
    }
}
