<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		view ()->composer ( '*', 'App\Http\ViewComposers\UserComposer' );
		view ()->composer ( '*', 'App\Http\ViewComposers\DataComposer' );
	}
	
	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}
}
