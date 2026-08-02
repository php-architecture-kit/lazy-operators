<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\BcMath;

use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcAddFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcDivFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcMulFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcSubFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Comparison\BcCompFunction;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Static\FloatLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Foundation\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;
use PhpArchitecture\LazyOperators\Foundation\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;

/**
 * Static factories for the ext-bcmath arbitrary-precision functions (https://www.php.net/manual/en/book.bc.php).
 * There is no bcscale()-backed default: every method takes $scale explicitly, mirroring how
 * Extension\Math\Rounding\RoundFunction takes $precision explicitly instead of reading global state.
 */
final class BcMath
{
    use DecoratesNodes;
    use WrapsRawValues;

    public static function add(
        int|float|string|NumberValue $left,
        int|float|string|NumberValue $right,
        null|int|IntegerValue $scale = null,
        ?PipelineConfig $config = null,
    ): NumberValue {
        return self::decorateNumber(
            new BcAddFunction(self::wrapRawScalar($left), self::wrapRawScalar($right), self::wrapScale($scale)),
            $config ?? new PipelineConfig(),
        );
    }

    public static function sub(
        int|float|string|NumberValue $left,
        int|float|string|NumberValue $right,
        null|int|IntegerValue $scale = null,
        ?PipelineConfig $config = null,
    ): NumberValue {
        return self::decorateNumber(
            new BcSubFunction(self::wrapRawScalar($left), self::wrapRawScalar($right), self::wrapScale($scale)),
            $config ?? new PipelineConfig(),
        );
    }

    public static function mul(
        int|float|string|NumberValue $left,
        int|float|string|NumberValue $right,
        null|int|IntegerValue $scale = null,
        ?PipelineConfig $config = null,
    ): NumberValue {
        return self::decorateNumber(
            new BcMulFunction(self::wrapRawScalar($left), self::wrapRawScalar($right), self::wrapScale($scale)),
            $config ?? new PipelineConfig(),
        );
    }

    public static function div(
        int|float|string|NumberValue $dividend,
        int|float|string|NumberValue $divisor,
        null|int|IntegerValue $scale = null,
        ?PipelineConfig $config = null,
    ): NumberValue {
        return self::decorateNumber(
            new BcDivFunction(self::wrapRawScalar($dividend), self::wrapRawScalar($divisor), self::wrapScale($scale)),
            $config ?? new PipelineConfig(),
        );
    }

    public static function comp(
        int|float|string|NumberValue $left,
        int|float|string|NumberValue $right,
        null|int|IntegerValue $scale = null,
        ?PipelineConfig $config = null,
    ): NumberValue {
        return self::decorateNumber(
            new BcCompFunction(self::wrapRawScalar($left), self::wrapRawScalar($right), self::wrapScale($scale)),
            $config ?? new PipelineConfig(),
        );
    }

    private static function wrapRawScalar(int|float|string|NumberValue $value): NumberValue
    {
        if ($value instanceof NumberValue) {
            return $value;
        }

        if (is_string($value)) {
            assert(is_numeric($value));

            return new FloatLiteral((float) $value);
        }

        $wrapped = self::wrap($value);
        assert($wrapped instanceof NumberValue);

        return $wrapped;
    }

    private static function wrapScale(null|int|IntegerValue $scale): ?IntegerValue
    {
        if ($scale instanceof IntegerValue) {
            return $scale;
        }

        return $scale === null ? null : new IntLiteral($scale);
    }
}
