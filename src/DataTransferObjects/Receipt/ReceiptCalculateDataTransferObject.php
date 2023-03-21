<?php

namespace Sashalenz\DeliveryAuto\DataTransferObjects\Receipt;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Sashalenz\DeliveryAuto\DataTransferObjects\DeliveryDataTransferObject;

class ReceiptCalculateDataTransferObject extends DeliveryDataTransferObject
{
    public string $culture;
    public string $areasSendId;
    public string $areasResiveId;
    public string $warehouseSendId;
    public string $warehouseResiveId;
    public string $areasSendIdName;
    public string $areasResiveIdName;
    public string $warehouseSendIdName;
    public string $warehouseResiveIdName;
    public ?float $cashOnDeliveryValue;
    public ?int $cashOnDeliveryValuta;
    public ?float $insuranceValue;
    public ?float $insuranceCost;
    public Carbon $dateSend;
    public Carbon $dateResive;
    public int $climbingToFloor;
    public int $descentFromFloor;
    public int $deliveryScheme;
    public Collection $category;
    public Collection $dopUslugaClassificator;
    public ?float $categorySumma;
    public ?float $allSumma;
    public bool $status;
    public bool $denyIssue;
    public ?bool $economDelivery;
    public ?bool $economPickUp;
    public ?bool $isGidrobort;
    public ?bool $isOverSize;
    public bool $isPostomat;
    public string $comment;
    public ?float $summaryTransportCost;
    public ?float $summaryDuCost;
    public ?float $summaryOformlenieCost;
    public int $currency;
    public int $viewType;

    public static function fromArray(array $array): self
    {
        return new self([
            'culture' => $array['culture'],
            'areasSendId' => $array['areasSendId'],
            'areasResiveId' => $array['areasResiveId'],
            'warehouseSendId' => $array['warehouseSendId'],
            'warehouseResiveId' => $array['warehouseResiveId'],
            'areasSendIdName' => $array['areasSendIdName'],
            'areasResiveIdName' => $array['areasResiveIdName'],
            'warehouseSendIdName' => $array['warehouseSendIdName'],
            'warehouseResiveIdName' => $array['warehouseResiveIdName'],
            'CashOnDeliveryValue' => (float) $array['CashOnDeliveryValue'],
            'CashOnDeliveryValuta' => (int) $array['CashOnDeliveryValuta'],
            'InsuranceValue' => (float) $array['InsuranceValue'],
            'InsuranceCost' => (float) $array['InsuranceCost'],
            'dateSend' => Carbon::parse($array['dateSend']),
            'dateResive' => Carbon::parse($array['dateResive']),
            'climbingToFloor' => (int) $array['climbingToFloor'],
            'descentFromFloor' => (int) $array['descentFromFloor'],
            'deliveryScheme' => (int) $array['deliveryScheme'],
            'category' => CategoryDataTransferObject::collectFromArray($array['category']),
            'dopUslugaClassificator' => DopUslugaClassificationDataTransferObject::collectFromArray($array['dopUslugaClassificator']),
            'categorySumma' => $array['categorySumma'] ?? null,
            'allSumma' => $array['allSumma'],
            'status' => (bool) $array['status'],
            'denyIssue' => (bool) $array['denyIssue'],
            'EconomDelivery' => (bool) $array['EconomDelivery'],
            'EconomPickUp' => (bool) $array['EconomPickUp'],
            'IsGidrobort' => (bool) $array['IsGidrobort'],
            'IsOverSize' => (bool) $array['IsOverSize'],
            'isPostomat' => (bool) $array['isPostomat'],
            'comment' => $array['comment'],
            'SummaryTransportCost' => $array['SummaryTransportCost'] ?? null,
            'SummaryDuCost' => $array['SummaryDuCost'] ?? null,
            'currency' => $array['currency'],
            'viewType' => $array['viewType'],
        ]);
    }
}
