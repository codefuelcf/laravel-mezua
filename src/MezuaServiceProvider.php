<?php

/**
 * Laravel Sms Package - Mezua
 */

namespace Codefuelcf\Mezua;

use Illuminate\Support\ServiceProvider;

class MezuaServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		// Laod package migrations
		$this->publishes([
			__DIR__.'/../database/migrations/' => database_path('migrations')
		], 'migration');

		// Load package config
		$this->publishes([
			__DIR__.'./../config/sms.php' => config_path('sms.php'),
		], 'config');

		// Load package controllers
		$this->publishes([
			__DIR__.'./../controller/SmsController.php' => app_path('Http/Controllers/SmsController.php'),
		], 'controller');
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}
}
