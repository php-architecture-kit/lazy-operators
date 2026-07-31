<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\BcMath;

use BcMath\Number;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;

interface PrecisionNumberValue extends NumberValue
{
    /**
     * Native counterpart to __invoke(): int|float, used only within Foundation\Extension\BcMath so
     * chained BcMath nodes can pass values to each other without a lossy round-trip through int|float.
     */
    public function bcValue(): Number;
}
