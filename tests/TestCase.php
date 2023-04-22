<?php

namespace DrH\Buni\Tests;

use DrH\Buni\BuniServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Event;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    /**
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [BuniServiceProvider::class];
    }

    public function getEnvironmentSetUp($app): void
    {
        $migration = include __DIR__ . '/../database/migrations/create_buni_stk_requests_table.php.stub';
        $migration->up();

        $migration = include __DIR__ . '/../database/migrations/create_buni_stk_callbacks_table.php.stub';
        $migration->up();
    }
}
