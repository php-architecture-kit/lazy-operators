<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Static\Value;

trait WrapsRawValues
{
    private static function wrap(mixed $value): Expression
    {
        return $value instanceof Expression ? $value : new Value($value);
    }
}
