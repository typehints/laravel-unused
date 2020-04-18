<?php

namespace TypeHints\Unused;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $configPath = __DIR__.'/../config/laravelunused.php';

        $this->mergeConfigFrom($configPath, 'laravelunused');
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravelunused');

        $this->publishRoutes();

        $this->publishAssets();

        $this->publishConfig();
    }

    public function publishRoutes()
    {
        $routeConfig = [
            'namespace'  => 'TypeHints\Unused\Controllers',
            'prefix'     => $this->app['config']->get('laravelunused.route_prefix'),
            'middleware' => $this->app['config']->get('laravelunused.middleware'),
        ];

        $this->app['router']->group($routeConfig, function ($router) {
            $router->get('/{view?}', [
                'uses' => 'LaravelUnusedController',
                'as'   => 'laravelunused.dashboard',
            ])->where('view', '(.*)');

            $router->delete('/delete/{view}', [
                'uses' => 'LaravelUnusedController@delete',
                'as'   => 'laravelunused.delete',
            ]);
        });
    }

    /**
     * @return void
     */
    protected function publishAssets(): void
    {
        $this->publishes([__DIR__.'/../public' => public_path('vendor/laravelunused')], 'laravelunused-assets');
    }

    /**
     * @return void
     */
    protected function publishConfig(): void
    {
        $this->publishes([__DIR__.'/../config/laravelunused.php' => config_path('laravelunused.php')], 'config');
    }
}
