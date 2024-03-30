<?php

namespace DrH\Buni;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BuniServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-buni')
            ->hasConfigFile()
            ->hasMigrations([
                'create_buni_stk_requests_table', 'create_buni_stk_callbacks_table', 'create_buni_ipns_table',
                ])
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToRunMigrations();
            });
    }

    public function boot(): BuniServiceProvider|static
    {
        parent::boot();

        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');

        $this->requireHelperScripts();

        return $this;
    }

    private function requireHelperScripts(): void
    {
        $files = glob(__DIR__ . '/Support/*.php');
        foreach ($files as $file) {
            include_once $file;
        }
    }
}
