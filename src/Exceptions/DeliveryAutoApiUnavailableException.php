<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\Exceptions;

/**
 * Thrown when the Delivery-Auto API is reachable but unresponsive — connection
 * reset, DNS failure, timeout, or 5xx server error.
 *
 * Distinct from the generic DeliveryAutoException (which signals a 4xx or
 * application-level `status: false` response) so consumers and error trackers
 * can group "vendor is down" separately from "this particular request was
 * rejected".
 */
class DeliveryAutoApiUnavailableException extends DeliveryAutoException {}
