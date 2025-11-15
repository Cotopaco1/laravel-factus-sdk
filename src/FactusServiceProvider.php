<?php

namespace Cotopaco\Factus;

use Illuminate\Support\ServiceProvider;

class FactusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/factus.php',
            'factus'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/factus.php' => config_path('factus.php'),
        ], 'factus-config');
    }
}
