<?php

namespace AsayHome\AsayComponents;

use Illuminate\Support\ServiceProvider;

class AsayComponentsProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/asay-components.php' => config_path('asay-components.php'),
        ]);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'asay-components');
        // $this->loadViewsFrom(__DIR__ . '/../resources/views', 'asay-components');
    }
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/asay-components.php',
            'asay-components'
        );
    }
}
