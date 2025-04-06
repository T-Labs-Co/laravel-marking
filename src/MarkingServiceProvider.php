<?php

namespace TLabsCo\LaravelMarking;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TLabsCo\LaravelMarking\Commands\MarkingCommand;

class MarkingServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-marking')
            ->hasConfigFile()
            ->hasMigration('create_laravel_marking_table')
            ->hasCommand(MarkingCommand::class);
    }
}
