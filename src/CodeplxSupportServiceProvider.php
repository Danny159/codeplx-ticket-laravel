<?php

namespace Codeplx\LaravelCodeplxSupport;

use Illuminate\Support\ServiceProvider;

class CodeplxSupportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Ensure CODEPLX_API_KEY is in the .env file
        if (! env('CODEPLX_API_KEY')) {
            throw new \Exception('Please add CODEPLX_API_KEY to your .env file');
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load the routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load the views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'codeplx-support');

        // Publish the routes
        $this->publishes([
            __DIR__.'/routes/web.php' => base_path('routes/codeplx-support.php'),
        ], 'codeplx-support-routes');
    }
}
