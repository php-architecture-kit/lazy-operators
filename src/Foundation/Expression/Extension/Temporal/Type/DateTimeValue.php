<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\ObjectValue;

/**
 * A point in time, carrying the zone its calendar and wall-clock operations run in.
 *
 * @extends ObjectValue<DateTimeImmutable>
 */
interface DateTimeValue extends ObjectValue
{
    public function __invoke(): DateTimeImmutable;
}
