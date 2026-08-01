<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support;

use BcMath\Number;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\PrecisionNumberValue;

trait NormalizesPrecisionValues
{
    private static function normalize(Number|Expression $value): PrecisionNumberValue
    {
        return match (true) {
            $value instanceof PrecisionNumberValue => $value,
            $value instanceof Number => new BcNumberLiteral($value),
            default => new PrecisionNumberAdapter($value),
        };
    }
}
