<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use Sashalenz\DeliveryAuto\DeliveryAutoServiceProvider;
use Sashalenz\DeliveryAuto\SessionStore;
use Spatie\LaravelData\LaravelDataServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        SessionStore::clear();
    }

    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            LaravelDataServiceProvider::class,
            DeliveryAutoServiceProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('delivery-auto-api.url', 'https://www.delivery-auto.com/api/v4/Public/');
        $app['config']->set('delivery-auto-api.public_key', 'CDBFE2D5-BF02-4C0D-B7D6-5CF277761C50');
        $app['config']->set('delivery-auto-api.secret_key', '6c131f01b99dfac3529d0cd68b1d6649');
        $app['config']->set('delivery-auto-api.username', null);
        $app['config']->set('delivery-auto-api.password', null);
        $app['config']->set('cache.default', 'array');
    }
}
