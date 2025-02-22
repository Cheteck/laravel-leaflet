<?php

namespace IJIDeals\Laraleaflet;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use IJIDeals\Laraleaflet\View\Components\LaravelMap;

class LaraleafletServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/views','LaraLeaflet');

        // Blade::component('laravel-map', LaravelMap::class);
        Blade::componentNamespace('IJIDeals\\Laraleaflet\\View\\Components', 'laravel-map');
    }
}
