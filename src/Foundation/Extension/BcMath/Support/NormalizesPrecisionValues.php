<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support;

use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\PrecisionNumberValue;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;

trait NormalizesPrecisionValues
{
    private static function normalize(NumberValue $value): PrecisionNumberValue
    {
        return $value instanceof PrecisionNumberValue ? $value : new NumberValueToPrecisionAdapter($value);
    }
}
