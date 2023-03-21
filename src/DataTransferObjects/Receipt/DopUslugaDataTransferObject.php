<?php

namespace Sashalenz\DeliveryAuto\DataTransferObjects\Receipt;

use Sashalenz\DeliveryAuto\DataTransferObjects\DeliveryDataTransferObject;

class DopUslugaDataTransferObject extends DeliveryDataTransferObject
{
    public string $uslugaId;
    public string $name;
    public float $cost;
    public int $count;
    public int $classification;
    public ?float $minWidth = null;
    public ?float $maxWidth = null;
    public float $summa;
    public ?string $comment;
    public int $currency;

    public static function fromArray(array $array): self
    {
        return new self([
            'uslugaId' => $array['uslugaId'],
            'name' => $array['name'],
            'cost' => (float) $array['cost'],
            'count' => (int) $array['count'],
            'classification' => (int) $array['classification'],
            'minWidth' => $array['minWidth'] ?? null,
            'maxWidth' => $array['maxWidth'] ?? null,
            'summa' => (float) $array['summa'],
            'comment' => $array['comment'] ?? null,
            'currency' => (int) $array['currency'],
        ]);
    }
}
