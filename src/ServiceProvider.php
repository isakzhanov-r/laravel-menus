<?php

namespace IsakzhanovR\Menus;

use IsakzhanovR\Menus\Support\Menu;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $this->publishConfig();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/settings.php', 'laravel_menus');
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/settings.php' => config_path('laravel_menus.php'),
        ], 'config');
    }
}
