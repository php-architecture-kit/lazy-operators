<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Exception;

use InvalidArgumentException;

final class UnwrappableValueException extends InvalidArgumentException implements LazyOperatorsException
{
    public static function create(mixed $value): self
    {
        return new self(sprintf(
            'Cannot wrap a raw "%s" as an Expression literal: only int, float, bool, string and array are supported.',
            get_debug_type($value),
        ));
    }
}
