<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Support;

use PhpArchitecture\LazyOperators\Foundation\Exception\UnwrappableValueException;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Static\ArrayLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\BoolLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\FloatLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\StringLiteral;

trait WrapsRawValues
{
    private static function wrap(mixed $value): Expression
    {
        return match (true) {
            $value instanceof Expression => $value,
            is_int($value) => new IntLiteral($value),
            is_float($value) => new FloatLiteral($value),
            is_bool($value) => new BoolLiteral($value),
            is_string($value) => new StringLiteral($value),
            is_array($value) => new ArrayLiteral($value),
            default => throw UnwrappableValueException::create($value),
        };
    }

    /**
     * @template T of Expression
     *
     * @param class-string<T> $type
     *
     * @return T
     */
    private static function wrapAs(string $type, mixed $value): Expression
    {
        $wrapped = self::wrap($value);
        assert($wrapped instanceof $type);

        return $wrapped;
    }
}
