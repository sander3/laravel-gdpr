<?php

namespace Soved\Laravel\Gdpr;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Soved\Laravel\Gdpr\Console\Commands\Cleanup;

class GdprServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerCommands();
    }

    /**
     * Register the GDPR routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'prefix'     => config('gdpr.uri'),
            'namespace'  => 'Soved\Laravel\Gdpr\Http\Controllers',
            'middleware' => config('gdpr.middleware'),
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    /**
     * Register the GDPR commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Cleanup::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
        $this->offerPublishing();
    }

    /**
     * Setup the configuration for GDPR.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/gdpr.php', 'gdpr'
        );
    }

    /**
     * Setup the resource publishing groups for GDPR.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/gdpr.php' => config_path('gdpr.php'),
            ], 'gdpr-config');
        }
    }
}
