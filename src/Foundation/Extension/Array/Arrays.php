<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Array;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Array\Aggregate\ProductFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Array\Aggregate\SumFunction;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

/**
 * Static factories for a growing subset of PHP's Array book (https://www.php.net/manual/en/book.array.php).
 * Unlike Math, this does not (yet) cover every native array function — currently: sum, product.
 */
class Arrays
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
        $config = new PipelineConfig();
        $values = array_map(
            static fn (int|float|Expression $value) => self::decorateNumber(self::wrapAs(NumberValue::class, $value), $config),
            [$first, ...$rest],
        );

        return self::decorate(new $class(...$values), $config);
    }
}
