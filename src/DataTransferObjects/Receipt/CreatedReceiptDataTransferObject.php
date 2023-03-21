<?php

namespace Sashalenz\DeliveryAuto\DataTransferObjects\Receipt;

use Sashalenz\DeliveryAuto\DataTransferObjects\DeliveryDataTransferObject;

class CreatedReceiptDataTransferObject extends DeliveryDataTransferObject
{
    public string $id;
    public string $number;
    public ?float $totalCost;
    public float $insuranceCost;
    public float $comissionGM;
    public string $comment;
    public array $egs;

    public static function fromArray(array $array): self
    {
        return new self([
// "egs": [//Массив единиц груза
// {
//     "Id": "3fed9940-b094-4236-a3b0-728a83123eca",
// "PartnerNumber": null,
// "Number": "9900000000002002151"
// }
// ]

            'id' => $array['Id'],
            'number' => $array['Number'],
            'totalCost' => (float) $array['TotallCost'],
            'insuranceCost' => (float) $array['InsuranceCost'],
            'comissionGM' => (float) $array['ComissionGM'],
            'comment' => $array['Comment'],
            'egs' => $array['egs']
        ]);
    }
}
