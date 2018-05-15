<?php

namespace Dialect\Gdpr;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class GdprServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		$this->registerRoutes();

		// Load standard issue migrations
		$timestamp = date('Y_m_d_His');
		$this->publishes([
			__DIR__.'./migrations/add_last_activity_and_accepted_gdpr_to_users_table.php' => database_path('migrations/'.$timestamp.'add_last_activity_and_accepted_gdpr_to_users_table.php'),
		], 'migrations');
	}

	/**
	 * Register the GDPR routes.
	 *
	 * @return void
	 */
	protected function registerRoutes() {
		Route::group([
			'prefix' => config('gdpr.uri'),
			'namespace' => 'Dialect\Package\Gdpr\Http\Controllers',
			'middleware' => config('gdpr.middleware'),
		], function() {
			$this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
		});
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->configure();
		$this->offerPublishing();
		$this->addScheduledJobs();
	}

	/**
	 * Setup the configuration for GDPR.
	 *
	 * @return void
	 */
	protected function configure() {
		$this->mergeConfigFrom(__DIR__ . '/../config/gdpr.php', 'gdpr');
	}

	/**
	 * Setup the resource publishing groups for GDPR.
	 *
	 * @return void
	 */
	protected function offerPublishing() {
		if($this->app->runningInConsole()) {
			$this->publishes([
				__DIR__ . '/../config/gdpr.php' => config_path('gdpr.php'),
			], 'gdpr-config');
		}
	}

	protected function addScheduledJobs() {
		$this->app->singleton('dialect.package.console.kernel', function($app) {
			$dispatcher = $app->make(\Illuminate\Contracts\Events\Dispatcher::class);

			return new \Dialect\Package\Console\Kernel($app, $dispatcher);
		});

		$this->app->make('dialect.package.console.kernel');
	}
}
