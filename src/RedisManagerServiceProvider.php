<?php

namespace Encore\RedisManager;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RedisManagerServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerResources();

        $this->definePublishing();
    }

    /**
     * Register the Redis manager routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'prefix' => config('redis-manager.uri', 'redis-manager'),
            'namespace' => 'Encore\RedisManager\Http\Controllers',
            'middleware' => config('redis-manager.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register the Redis manager resources.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'redis-manager');
    }

    /**
     * Define the publishing.
     *
     * @return void
     */
    public function definePublishing()
    {
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/redis-manager'),
        ], 'redis-manager-assets');

        $this->publishes([
            __DIR__.'/../fonts' => public_path('vendor/redis-manager/fonts'),
        ], 'redis-manager-assets');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/redis-manager.php' => config_path('redis-manager.php'),
            ], 'redis-manager-config');
        }
    }
}
