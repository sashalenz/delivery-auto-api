<?php

namespace Sashalenz\DeliveryAuto\ApiModels;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Sashalenz\DeliveryAuto\DataTransferObjects\BaseDataTransferObject;
use Sashalenz\DeliveryAuto\DataTransferObjects\Receipt\CreatedAddressDataTransferObject;
use Sashalenz\DeliveryAuto\DataTransferObjects\Receipt\CreatedClientDataTransferObject;
use Sashalenz\DeliveryAuto\DataTransferObjects\Receipt\CreatedReceiptDataTransferObject;
use Sashalenz\DeliveryAuto\DataTransferObjects\Receipt\DeliverySchemaDataTransferObject;
use Sashalenz\DeliveryAuto\DataTransferObjects\Receipt\DopUslugaClassificationDataTransferObject;
use Sashalenz\DeliveryAuto\DataTransferObjects\Receipt\ReceiptCalculateDataTransferObject;
use Sashalenz\DeliveryAuto\DataTransferObjects\Receipt\ReceiptDataTransferObject;
use Sashalenz\DeliveryAuto\DataTransferObjects\Receipt\SenderDataTransferObject;
use Sashalenz\DeliveryAuto\DataTransferObjects\Receipt\TariffCategoryDataTransferObject;
use Sashalenz\DeliveryAuto\DataTransferObjects\Warehouse\WarehouseInfoDataTransferObject;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryException;

final class Receipt extends BaseModel
{
    /**
     * @return ReceiptDataTransferObject
     * @throws DeliveryException
     */
    public function getReceiptDetails(): ReceiptDataTransferObject
    {
        return ReceiptDataTransferObject::fromArray(
            $this->method('GetReceiptDetails')
                ->validate([
                    'number' => ['required', 'string'],
                ])
                ->request()
                ->toArray()
        );
    }

    /**
     * @return Carbon
     * @throws DeliveryException
     */
    public function getDateArrival(): Carbon
    {
        return Carbon::parse(
            $this->method('GetDateArrival')
                ->validate([
                    'areasSendId' => ['required', 'uuid'],
                    'areasResiveId' => ['required', 'uuid'],
                    'dateSend' => ['required', 'date', 'date_format:d.m.Y', 'after_or_equal:today'],
                    'currency' => ['required', 'numeric'],
                    'warehouseSendId' => ['nullable', 'uuid'],
                    'warehouseResiveId' => ['nullable', 'uuid'],
                ])
                ->request()
                ->get('arrivalDate')
        );
    }

    /**
     * @return Collection
     * @throws DeliveryException
     */
    public function getDopUslugiClassification(): Collection
    {
        return $this->method('GetDopUslugiClassification')
            ->validate([
                'CitySendId' => ['required', 'uuid'],
                'CityReceiveId' => ['required', 'uuid'],
                'currency' => ['required', 'numeric'],
                'formalization' => ['nullable', 'boolean'],
            ])
            ->request()
            ->map(fn (array $array) => DopUslugaClassificationDataTransferObject::fromArray($array));
    }

    /**
     * @return Collection
     * @throws DeliveryException
     */
    public function getTariffCategory(): Collection
    {
        return $this->method('GetTariffCategory')
            ->validate([
                'CitySendId' => ['required', 'uuid'],
                'CityReceiveId' => ['required', 'uuid'],
                'WarehouseReceiveId' => ['required', 'uuid'],
            ])
            ->request()
            ->map(fn (array $array) => TariffCategoryDataTransferObject::fromArray($array));
    }

    /**
     * @return Collection
     * @throws DeliveryException
     */
    public function getDeliveryScheme(): Collection
    {
        return $this->method('GetDeliveryScheme')
            ->validate([
                'CitySendId' => ['required', 'uuid'],
                'CityReceiveId' => ['required', 'uuid'],
                'WarehouseReceiveId' => ['required', 'uuid'],
            ])
            ->request()
            ->map(fn (array $array) => DeliverySchemaDataTransferObject::fromArray($array));
    }

    /**
     * @return ReceiptCalculateDataTransferObject
     * @throws DeliveryException
     */
    public function postReceiptCalculate(): ReceiptCalculateDataTransferObject
    {
        return ReceiptCalculateDataTransferObject::fromArray(
            $this->method('PostReceiptCalculate')
                ->validate([
                    'areasSendId' => ['required', 'uuid'],
                    'areasResiveId' => ['required', 'uuid'],
                    'warehouseSendId' => ['required', 'uuid'],
                    'warehouseResiveId' => ['required', 'uuid'],
                    'InsuranceValue' => ['required', 'numeric', 'min:0'],
                    'CashOnDeliveryValue' => ['required', 'numeric', 'min:0'],
                    'dateSend' => ['required', 'date', 'date_format:d.m.Y', 'after_or_equal:today'],
                    'deliveryScheme' => ['required', 'numeric'],
                    'category.*.categoryId' => ['required', 'uuid'],
                    'category.*.countPlace' => ['required', 'numeric', 'min:1'],
                    'category.*.helf' => ['required', 'numeric'],
                    'category.*.size' => ['required', 'numeric'],
                    'dopUslugaClassificator.*.dopUsluga.*.uslugaId' => ['required', 'uuid'],
                    'dopUslugaClassificator.*.dopUsluga.*.count' => ['required', 'numeric', 'min:1'],
                ])
                ->post()
                ->request()
                ->toArray()
        );
    }

    /**
     * @return Collection
     * @throws DeliveryException
     */
    public function getClientCards(): Collection
    {
        return $this->method('GetClientCards')
            ->auth()
            ->request()
            ->map(fn (array $array) => BaseDataTransferObject::fromArray($array));
    }

    /**
     * @return Collection
     * @throws DeliveryException
     */
    public function getClientInvoices(): Collection
    {
        return $this->method('GetClientInvoices')
            ->auth()
            ->request()
            ->map(fn (array $array) => BaseDataTransferObject::fromArray($array));
    }

    /**
     * @return Collection
     * @throws DeliveryException
     */
    public function getCargoCategory(): Collection
    {
        return $this->method('GetCargoCategory')
            ->auth()
            ->request()
            ->map(fn (array $array) => BaseDataTransferObject::fromArray($array));
    }

    public function postCreateReceipts(): Collection
    {
        return $this->method('PostCreateReceipts')
            ->validate([
                'receiptsList' => 'array',
            ])
            ->auth()
            ->debug()
            ->dataKey('receipts')
            ->post()
            ->request()
            ->map(fn (array $array) => CreatedReceiptDataTransferObject::fromArray($array));
    }

    public function getSenderList(): Collection
    {
        return $this->method('GetSenderList')
            ->auth()
            ->request()
            ->map(fn (array $array) => SenderDataTransferObject::fromArray($array));
    }

    public function postCreateAddressOrClient(): Collection
    {
        $response = $this->method('PostCreateAddressOrClient')
            ->auth()
            ->post()
            ->request();

        return collect([
            'address' => CreatedAddressDataTransferObject::fromArray($response['address']),
            'client' => CreatedClientDataTransferObject::fromArray($response['account'])
        ]);
    }

    public function getWarehousesListByCity(): Collection
    {
        return $this->method('GetWarehousesListByCity')
            ->validate([
                'CityId' => ['required', 'uuid'],
                'DirectionType' => ['required', 'boolean']
            ])
            ->request()
            ->map(fn (array $array) => WarehouseInfoDataTransferObject::fromArray($array));
    }
}
