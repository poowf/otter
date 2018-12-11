<?php

namespace Poowf\Otter;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class OtterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerPublishing();
        $this->registerResources();
    }

    /**
     * Register the Otter routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'prefix' => config('otter.uri', 'otter'),
            'namespace' => 'Poowf\Otter\Http\Controllers',
            'middleware' => config('otter.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register the Otter resources.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'otter');
    }

    /**
     * Merge configuration.
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/otter.php', 'otter'
        );
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/otter'),
            ], 'otter-assets');
            
            $this->publishes([
                __DIR__.'/../stubs/OtterServiceProvider.stub' => app_path('Providers/OtterServiceProvider.php'),
            ], 'otter-provider');

            $this->publishes([
                __DIR__.'/../config/otter.php' => config_path('otter.php'),
            ], 'otter-config');
        }
    }

    /**
     * Setup the commands for Otter.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands([
            Console\InstallCommand::class,
            Console\PublishCommand::class,
            Console\ResourceCommand::class,
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('otter', function ($app) {
            return $app->make(Otter::class);
        });

        $this->mergeConfig();
        $this->registerCommands();
    }
}