<?php

namespace Sashalenz\DeliveryAuto;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DeliveryServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('delivery-api')
            ->hasConfigFile();
    }
}
