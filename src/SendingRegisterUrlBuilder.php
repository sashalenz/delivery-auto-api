<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto;

use Sashalenz\DeliveryAuto\Enums\Culture;

/**
 * §6.18 SendingRegister — pickup-order print form. Lives at a different
 * URL prefix (`uk-UA/SharedForms/SendingRegister`) than the JSON API and
 * returns HTML, not JSON. We expose only a URL builder here (mirroring NP-API's
 * `Marking::printMarking()`) so consumers can hand the URL to a browser /
 * download client / proxy of their choice.
 */
final class SendingRegisterUrlBuilder
{
    public const FORM_TYPE_DETAILED = 0;

    public const FORM_TYPE_SUMMARY = 1;

    public static function url(string $id, int $type = self::FORM_TYPE_SUMMARY, Culture $culture = Culture::UkUA): string
    {
        $base = (string) config('delivery-auto-api.shared_forms_url', 'https://www.delivery-auto.com/');
        $base = rtrim($base, '/');

        return sprintf(
            '%s/%s/SharedForms/SendingRegister?%s',
            $base,
            $culture->value,
            http_build_query(['id' => $id, 'type' => $type])
        );
    }
}
