<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto;

use GuzzleHttp\Cookie\CookieJar;

/**
 * In-memory cookie jar for the login-session auth flow used by Cabinet (§5)
 * and Logs (§7) endpoints. The session is process-local — each PHP request
 * starts fresh and must call PostLogin before any session-protected endpoint.
 *
 * For long-running consumers (queue workers, daemon CLI) this means a single
 * login + many session calls within the same process. For web requests inside
 * a Laravel app the consumer is responsible for re-logging-in or persisting
 * the session externally if needed.
 */
final class SessionStore
{
    private static ?CookieJar $jar = null;

    public static function store(CookieJar $jar): void
    {
        if (self::$jar === null) {
            self::$jar = $jar;

            return;
        }

        // Merge incoming cookies into the existing jar so new auth tokens
        // overwrite their predecessors instead of replacing the whole jar.
        foreach ($jar->getIterator() as $cookie) {
            self::$jar->setCookie($cookie);
        }
    }

    public static function cookies(): ?CookieJar
    {
        return self::$jar;
    }

    public static function clear(): void
    {
        self::$jar = null;
    }
}
