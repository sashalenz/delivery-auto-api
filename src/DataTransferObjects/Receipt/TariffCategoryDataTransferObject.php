<?php

namespace Sashalenz\DeliveryAuto\DataTransferObjects\Receipt;

use Sashalenz\DeliveryAuto\DataTransferObjects\DeliveryDataTransferObject;

class TariffCategoryDataTransferObject extends DeliveryDataTransferObject
{
    public string $id;
    public string $name;
    public ?float $minWidth = null;
    public ?float $maxWidth = null;
    public ?float $minSize = null;
    public ?float $maxSize = null;
    public ?float $length = null;
    public ?float $width = null;
    public ?float $height = null;
    public bool $requiredWeight;
    public bool $requiredSize;

    public static function fromArray(array $array): self
    {
        return new self([
            'id' => $array['id'],
            'name' => $array['name'],
            'minWidth' => $array['MinWidth'],
            'maxWidth' => $array['MaxWidth'],
            'minSize' => $array['MinSize'],
            'maxSize' => $array['MaxSize'],
            'length' => $array['Length'],
            'width' => $array['Width'],
            'height' => $array['Height'],
            'requiredWeight' => (bool) $array['RequiredWeight'],
            'requiredSize' => (bool) $array['RequiredSize'],
        ]);
    }
}
