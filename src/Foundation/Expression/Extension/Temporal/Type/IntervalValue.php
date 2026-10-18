<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type;

use DateInterval;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\ObjectValue;

/**
 * A calendar interval (years, months, days, hours, minutes, seconds). Every invocation returns
 * a fresh DateInterval, since the native class is mutable.
 *
 * @extends ObjectValue<DateInterval>
 */
interface IntervalValue extends ObjectValue
{
    public function __invoke(): DateInterval;
}
