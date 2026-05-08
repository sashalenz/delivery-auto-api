<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet\ResponseData;

use Spatie\LaravelData\Data;

final class UserInfoData extends Data
{
    public function __construct(
        public string $Id,
        public ?string $AccessLevel = null,
        public ?string $UserName = null,
        public ?int $SmsPhoneNumber = null,
        public ?bool $ClientType = null,
        public ?string $ClientNumber = null,
        public ?int $PhoneNumber = null,
        public ?string $CrmUserId = null,
        public ?string $Email = null,
        public ?bool $showHelpHide = null,
        public ?string $Photo = null,
        public ?string $RoleName = null,
        public ?bool $IsLoyaltyProgram = null,
        public ?int $AvailablePoints = null,
        public ?int $CurrentPoints = null,
        public ?string $City = null,
        public ?string $ConfirmedPhone = null,
    ) {}
}
