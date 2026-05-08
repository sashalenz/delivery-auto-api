<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet\RequestData;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

/**
 * §5.1 PostLogin — auth via username/password (separate from API-key HMAC).
 */
final class PostLoginRequest extends Data
{
    public function __construct(
        #[Required]
        public string $UserName,
        #[Required]
        public string $Password,
        public bool $RememberMe = true,
    ) {}
}
