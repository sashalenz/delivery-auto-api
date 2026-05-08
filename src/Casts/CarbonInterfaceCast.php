<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Casts;

use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Contracts\BaseData;
use Spatie\LaravelData\Exceptions\CannotCastDate;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

final class CarbonInterfaceCast implements Cast
{
    public function __construct(
        protected null|string|array $format = null,
        protected ?string $setTimeZone = null,
        protected ?string $timeZone = null,
    ) {}

    /**
     * @param  array<string, mixed>  $properties
     * @param  CreationContext<BaseData<mixed, mixed, array-key>>  $context
     *
     * @throws CannotCastDate
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?Carbon
    {
        /** @var Collection<int, string> $formats */
        $formats = collect((array) ($this->format ?? config('data.date_format')));

        if (is_array($value) && array_key_exists('date', $value)) {
            $this->timeZone = $value['timezone'];
            $value = $value['date'];
        }

        if ($value === '' || $value === null) {
            return null;
        }

        $datetime = $formats
            ->map(fn (string $format) => rescue(fn () => Carbon::createFromFormat(
                $format,
                $value,
                isset($this->timeZone) ? new DateTimeZone($this->timeZone) : null
            ), report: false))
            ->first(fn ($value) => (bool) $value);

        if ($datetime && $this->setTimeZone) {
            return $datetime->setTimezone(new DateTimeZone($this->setTimeZone));
        }

        return $datetime ?: null;
    }
}
