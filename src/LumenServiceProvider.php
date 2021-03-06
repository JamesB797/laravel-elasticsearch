<?php namespace Cviebrock\LaravelElasticsearch;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;


/**
 * Class ServiceProvider
 *
 * @package Cviebrock\LaravelElasticsearch
 */
class LumenServiceProvider extends BaseServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot() {

		$app = $this->app;

		// Later versions of lumen don't define VERSION and don't have vendor:publish
		$versionConstant = get_class($app) . '::VERSION';

		if (defined($versionConstant) && version_compare($app::VERSION, '5.0') >= 0) {
			// Laravel 5
			$configPath = realpath(__DIR__ . '/../config/elasticsearch.php');
			$this->publishes([
				$configPath => config_path('elasticsearch.php')
			]);
		}
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$app = $this->app;

		$app->singleton('elasticsearch.factory', function ($app) {
			return new Factory();
		});

		$app->singleton('elasticsearch', function ($app) {
			return new LumenManager($app, $app['elasticsearch.factory']);
		});
	}
}
