<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DeliveryAutoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('delivery-auto-api')
            ->hasConfigFile('delivery-auto-api');
    }
}
