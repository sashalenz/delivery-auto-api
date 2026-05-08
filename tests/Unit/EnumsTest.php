<?php

declare(strict_types=1);

use Sashalenz\DeliveryAuto\Enums\Currency;
use Sashalenz\DeliveryAuto\Enums\DeliveryScheme;
use Sashalenz\DeliveryAuto\Enums\OperationCode;
use Sashalenz\DeliveryAuto\Enums\OrderState;
use Sashalenz\DeliveryAuto\Enums\ReceiptStatus;

it('maps Currency::UAH to the documented vendor code 100000000', function () {
    expect(Currency::UAH->value)->toBe(100000000)
        ->and(Currency::UAH->label())->toBe('Гривня');
});

it('classifies ReceiptStatus terminal states correctly', function () {
    expect(ReceiptStatus::Issued->isFinal())->toBeTrue()
        ->and(ReceiptStatus::PartiallyIssued->isFinal())->toBeTrue()
        ->and(ReceiptStatus::Utilized->isFinal())->toBeTrue()
        ->and(ReceiptStatus::Sold->isFinal())->toBeTrue()
        ->and(ReceiptStatus::Cancelled->isFinal())->toBeTrue()
        ->and(ReceiptStatus::InTransit->isFinal())->toBeFalse()
        ->and(ReceiptStatus::Reserved->isFinal())->toBeFalse();
});

it('only treats fully-issued (Status=0) as success', function () {
    expect(ReceiptStatus::Issued->isSuccess())->toBeTrue()
        ->and(ReceiptStatus::PartiallyIssued->isSuccess())->toBeFalse()
        ->and(ReceiptStatus::Cancelled->isSuccess())->toBeFalse();
});

it('flags transit-related states', function () {
    expect(ReceiptStatus::InTransit->isInTransit())->toBeTrue()
        ->and(ReceiptStatus::ArrivedAtTransitWarehouse->isInTransit())->toBeTrue()
        ->and(ReceiptStatus::Unloading->isInTransit())->toBeTrue()
        ->and(ReceiptStatus::PreparingForCourier->isInTransit())->toBeTrue()
        ->and(ReceiptStatus::CourierDelivering->isInTransit())->toBeTrue()
        ->and(ReceiptStatus::Reserved->isInTransit())->toBeFalse()
        ->and(ReceiptStatus::Issued->isInTransit())->toBeFalse();
});

it('round-trips ReceiptStatus integer codes for all 14 states', function () {
    for ($code = 0; $code <= 13; $code++) {
        $enum = ReceiptStatus::from($code);
        expect($enum->value)->toBe($code)
            ->and($enum->label())->toBeString()
            ->and($enum->label())->not->toBe('');
    }
});

it('describes DeliveryScheme address requirements per scheme', function () {
    // Warehouse-Warehouse: no addresses
    expect(DeliveryScheme::WarehouseWarehouse->requiresPickupAddress())->toBeFalse()
        ->and(DeliveryScheme::WarehouseWarehouse->requiresDeliveryAddress())->toBeFalse()
        ->and(DeliveryScheme::WarehouseWarehouse->requiresSenderWarehouse())->toBeTrue()
        ->and(DeliveryScheme::WarehouseWarehouse->requiresReceiverWarehouse())->toBeTrue();

    // Door-Door: both addresses, no warehouses
    expect(DeliveryScheme::DoorDoor->requiresPickupAddress())->toBeTrue()
        ->and(DeliveryScheme::DoorDoor->requiresDeliveryAddress())->toBeTrue()
        ->and(DeliveryScheme::DoorDoor->requiresSenderWarehouse())->toBeFalse()
        ->and(DeliveryScheme::DoorDoor->requiresReceiverWarehouse())->toBeFalse();

    // Warehouse-Door: pickup at warehouse, delivery to address
    expect(DeliveryScheme::WarehouseDoor->requiresPickupAddress())->toBeFalse()
        ->and(DeliveryScheme::WarehouseDoor->requiresDeliveryAddress())->toBeTrue()
        ->and(DeliveryScheme::WarehouseDoor->requiresSenderWarehouse())->toBeTrue()
        ->and(DeliveryScheme::WarehouseDoor->requiresReceiverWarehouse())->toBeFalse();

    // Door-Warehouse: pickup at address, delivery to warehouse
    expect(DeliveryScheme::DoorWarehouse->requiresPickupAddress())->toBeTrue()
        ->and(DeliveryScheme::DoorWarehouse->requiresDeliveryAddress())->toBeFalse()
        ->and(DeliveryScheme::DoorWarehouse->requiresSenderWarehouse())->toBeFalse()
        ->and(DeliveryScheme::DoorWarehouse->requiresReceiverWarehouse())->toBeTrue();
});

it('classifies OrderState lifecycle', function () {
    expect(OrderState::Open->isOpen())->toBeTrue()
        ->and(OrderState::CreatedByClient->isOpen())->toBeTrue()
        ->and(OrderState::Closed->isOpen())->toBeFalse()
        ->and(OrderState::Cancelled->isOpen())->toBeFalse()
        ->and(OrderState::Cancelled->isCancelled())->toBeTrue()
        ->and(OrderState::Open->isCancelled())->toBeFalse();
});

it('labels OperationCode values', function () {
    expect(OperationCode::ReceiptIssuedAtWarehouse->label())->toBe('Оформлення квитанції на складі')
        ->and(OperationCode::CargoIssuedToClient1->label())->toBe('Видача вантажу клієнту');
});
