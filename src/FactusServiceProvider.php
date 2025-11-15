<?php

namespace Cotopaco\Factus;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Cotopaco\Factus\Commands\FactusCommand;

class FactusServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-factus-sdk')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_factus_sdk_table')
            ->hasCommand(FactusCommand::class);
    }
}
