<?php

namespace Sashalenz\DeliveryAuto\DataTransferObjects\Receipt;

use Carbon\Carbon;
use Sashalenz\DeliveryAuto\DataTransferObjects\DeliveryDataTransferObject;

class ReceiptDataTransferObject extends DeliveryDataTransferObject
{
    public string $id;
    public string $number;
    public Carbon $sendDate;
    public Carbon $receiveDate;
    public string $senderWarehouseName;
    public string $recipientWarehouseName;
    public float $discount;
    public float $totalCost;
    public int $status;
    public float $weight;
    public float $volume;
    public string $sites;
    public bool $paymentStatus;
    public int $currency;
    public ?string $insuranceCost = null;
    public ?string $insuranceCurrency = null;
    public ?string $pushStateCode = null;
    public ?string $codCost = null;
    public ?string $codCurrency = null;
    public string $senderPhone;
    public string $receiverPhone;
    public string $citySendName;
    public string $cityReceiveName;
    public int $deliveryType;
    public string $statusesDecoding;

    public static function fromArray(array $array): self
    {
        return new self([
            'id' => $array['id'],
            'number' => $array['number'],
            'sendDate' => Carbon::createFromFormat('Y-m-dTH:i:s', $array['SendDate']),
            'receiveDate' => Carbon::createFromFormat('Y-m-dTH:i:s', $array['ReceiveDate']),
            'senderWarehouseName' => $array['SenderWarehouseName'],
            'recipientWarehouseName' => $array['RecepientWarehouseName'],
            'discount' => (float) $array['Discount'],
            'totalCost' => (float) $array['TotalCost'],
            'status' => (int) $array['Status'],
            'weight' => (float) $array['Weight'],
            'volume' => (float) $array['Volume'],
            'sites' => $array['Sites'],
            'paymentStatus' => (bool) $array['PaymentStatus'],
            'currency' => (int) $array['Currency'],
            'insuranceCost' => $array['InsuranceCost'] ?? null,
            'insuranceCurrency' => $array['InsuranceCurrency'] ?? null,
            'pushStateCode' => $array['PushStateCode'] ?? null,
            'codCost' => $array['CodCost'] ?? null,
            'codCurrency' => $array['CodCurrency'] ?? null,
            'senderPhone' => $array['SenderPhone'],
            'receiverPhone' => $array['ReceiverPhone'],
            'citySendName' => $array['CitySendName'],
            'cityReceiveName' => $array['CityReceiveName'],
            'deliveryType' => (int) $array['DeliveryType'],
            'statusesDecoding' => $array['StatusesDecoding'],
        ]);
    }
}
