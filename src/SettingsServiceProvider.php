<?php

namespace KABBOUCHI\Settings;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use KABBOUCHI\Settings\Http\Controllers\GroupsController;
use KABBOUCHI\Settings\Http\Controllers\SettingsController;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerMigrations();

            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-settings.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'laravel-settings-migrations');

            $this->publishes([
                __DIR__.'/../resources/js/components' => base_path('resources/js/components/laravel-settings'),
            ], 'laravel-settings-components');
        }
    }

    /**
     * Register Passport's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if (Settings::$runsMigrations) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-settings');

        /** @var Router $router */
        $router = $this->app['router'];

        $router->get('api/laravel-settings/groups', [GroupsController::class, 'index']);
        $router->get('api/laravel-settings/groups/{group}', [GroupsController::class, 'show']);
        $router->post('api/laravel-settings/settings/{key}', [SettingsController::class, 'update']);
    }
}
