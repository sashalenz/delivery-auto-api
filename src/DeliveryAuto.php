<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto;

use Sashalenz\DeliveryAuto\ApiModels\Cabinet\Cabinet;
use Sashalenz\DeliveryAuto\ApiModels\Calculation\Calculation;
use Sashalenz\DeliveryAuto\ApiModels\Communication\Communication;
use Sashalenz\DeliveryAuto\ApiModels\Logs\Logs;
use Sashalenz\DeliveryAuto\ApiModels\Receipt\Receipt;
use Sashalenz\DeliveryAuto\ApiModels\Reference\Reference;
use Sashalenz\DeliveryAuto\ApiModels\Tracking\Tracking;

/**
 * Top-level entry-point.
 *
 * Each module factory returns a fresh builder; consumers compose calls fluently:
 *
 * ```
 * $regions = DeliveryAuto::reference()->cache(3600)->getRegionList(new GetRegionListRequest());
 * $calc    = DeliveryAuto::calculation()->postReceiptCalculate($request);
 * $created = DeliveryAuto::receipt()->postCreateReceipts($request);
 * $details = DeliveryAuto::tracking()->getReceiptDetails(new GetReceiptDetailsRequest(number: '0830047053'));
 * ```
 *
 * **Multi-sender** — HMAC-protected modules accept an optional credential pair so
 * apps with several Delivery-Auto company keypairs can switch sender per call:
 *
 * ```
 * DeliveryAuto::receipt(publicKey: $sender->public_key, secretKey: $sender->secret_key)
 *     ->getSenderList();
 *
 * // or after construction:
 * DeliveryAuto::receipt()
 *     ->withCredentials($sender->public_key, $sender->secret_key)
 *     ->postCreateReceipts($request);
 * ```
 *
 * Without overrides, the builder falls back to `config('delivery-auto-api.public_key')` /
 * `secret_key`.
 */
final class DeliveryAuto
{
    public static function reference(): Reference
    {
        return new Reference;
    }

    public static function tracking(?string $publicKey = null, ?string $secretKey = null): Tracking
    {
        return self::applyCredentials(new Tracking, $publicKey, $secretKey);
    }

    public static function calculation(): Calculation
    {
        return new Calculation;
    }

    public static function communication(): Communication
    {
        return new Communication;
    }

    public static function cabinet(): Cabinet
    {
        return new Cabinet;
    }

    public static function receipt(?string $publicKey = null, ?string $secretKey = null): Receipt
    {
        return self::applyCredentials(new Receipt, $publicKey, $secretKey);
    }

    public static function logs(): Logs
    {
        return new Logs;
    }

    /**
     * @template T of \Sashalenz\DeliveryAuto\ApiModels\BaseModel
     *
     * @param  T  $module
     * @return T
     */
    private static function applyCredentials($module, ?string $publicKey, ?string $secretKey)
    {
        if ($publicKey !== null && $secretKey !== null) {
            return $module->withCredentials($publicKey, $secretKey);
        }

        return $module;
    }
}
