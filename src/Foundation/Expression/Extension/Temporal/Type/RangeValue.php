<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DateTimeRange;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\ObjectValue;

/**
 * @extends ObjectValue<DateTimeRange>
 */
interface RangeValue extends ObjectValue
{
    public function __invoke(): DateTimeRange;
}
