<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\List;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\List\Aggregate\ProductFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\List\Aggregate\SumFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\WrapsRawValues;

/**
 * Static factories for reducing an unbounded list of values to a single scalar — a growing subset
 * of PHP's Array book (https://www.php.net/manual/en/book.array.php) that fits this shape;
 * currently: sum, product.
 */
class Lists
{
    use WrapsRawValues;
    use DecoratesNodes;

    public static function sum(int|float|Expression $first, int|float|Expression ...$rest): Expression
    {
        return self::variadicNumeric(SumFunction::class, $first, ...$rest);
    }

    public static function product(int|float|Expression $first, int|float|Expression ...$rest): Expression
    {
        return self::variadicNumeric(ProductFunction::class, $first, ...$rest);
    }

    /**
     * @param class-string<SumFunction|ProductFunction> $class
     */
    private static function variadicNumeric(string $class, int|float|Expression $first, int|float|Expression ...$rest): Expression
    {
        $config = new ExpressionTreeConfig();
        $values = array_map(
            static fn (int|float|Expression $value) => self::decorateNumber(self::wrapAs(NumberValue::class, $value), $config),
            [$first, ...$rest],
        );

        return self::decorate(new $class(...$values), $config);
    }
}
