<?php

namespace Sashalenz\DeliveryAuto\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Sashalenz\DeliveryAuto\DeliveryServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            DeliveryServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        /*
        include_once __DIR__.'/../database/migrations/create_delivery_api_table.php.stub';
        (new \CreatePackageTable())->up();
        */
    }
}
