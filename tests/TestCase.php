<?php

namespace Cotopaco\Factus\Tests;

use Cotopaco\Factus\FactusServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Cotopaco\\Factus\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app) : array
    {
        return [
            FactusServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app) : void
    {

        config()->set('database.default', 'testing');
        if (file_exists(__DIR__ . '/../.env.testing')) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..', '.env.testing');
            $dotenv->load();
        }
        // Configura los valores desde el .env.testing
        $app['config']->set('factus.sandbox', env('FACTUS_PRODUCTION', false));
        $app['config']->set('factus.sandbox_base_url', env('FACTUS_SANDBOX_BASE_URL'));
        $app['config']->set('factus.base_url', env('FACTUS_BASE_URL'));
        $app['config']->set('factus.client.id', env('FACTUS_CLIENT_ID'));
        $app['config']->set('factus.client.secret', env('FACTUS_CLIENT_SECRET'));
        $app['config']->set('factus.username', env('FACTUS_USERNAME'));
        $app['config']->set('factus.password', env('FACTUS_PASSWORD'));


        /*
         foreach (\Illuminate\Support\Facades\File::allFiles(__DIR__ . '/../database/migrations') as $migration) {
            (include $migration->getRealPath())->up();
         }
         */
    }
}
