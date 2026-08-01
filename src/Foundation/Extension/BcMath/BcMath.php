<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\BcMath;

use BcMath\Number;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcAddFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcDivFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcMulFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcSubFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Comparison\BcCompFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\BcNumberLiteral;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\NumberValueToPrecisionAdapter;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
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
        Number|int|float|string|Expression $left,
        Number|int|float|string|Expression $right,
        ?int $scale = null,
        ?PipelineConfig $config = null,
    ): PrecisionNumberValue {
        return self::decoratePrecisionNumber(
            new BcAddFunction(self::wrapRawScalar($left), self::wrapRawScalar($right), self::wrapScale($scale)),
            $config ?? new PipelineConfig(),
        );
    }

    public static function sub(
        Number|int|float|string|Expression $left,
        Number|int|float|string|Expression $right,
        ?int $scale = null,
        ?PipelineConfig $config = null,
    ): PrecisionNumberValue {
        return self::decoratePrecisionNumber(
            new BcSubFunction(self::wrapRawScalar($left), self::wrapRawScalar($right), self::wrapScale($scale)),
            $config ?? new PipelineConfig(),
        );
    }

    public static function mul(
        Number|int|float|string|Expression $left,
        Number|int|float|string|Expression $right,
        ?int $scale = null,
        ?PipelineConfig $config = null,
    ): PrecisionNumberValue {
        return self::decoratePrecisionNumber(
            new BcMulFunction(self::wrapRawScalar($left), self::wrapRawScalar($right), self::wrapScale($scale)),
            $config ?? new PipelineConfig(),
        );
    }

    public static function div(
        Number|int|float|string|Expression $dividend,
        Number|int|float|string|Expression $divisor,
        ?int $scale = null,
        ?PipelineConfig $config = null,
    ): PrecisionNumberValue {
        return self::decoratePrecisionNumber(
            new BcDivFunction(self::wrapRawScalar($dividend), self::wrapRawScalar($divisor), self::wrapScale($scale)),
            $config ?? new PipelineConfig(),
        );
    }

    public static function comp(
        Number|int|float|string|Expression $left,
        Number|int|float|string|Expression $right,
        ?int $scale = null,
        ?PipelineConfig $config = null,
    ): NumberValue {
        return self::decorateNumber(
            new BcCompFunction(self::wrapRawScalar($left), self::wrapRawScalar($right), self::wrapScale($scale)),
            $config ?? new PipelineConfig(),
        );
    }

    /**
     * BcAddFunction et al. only accept NumberValue (every constructor argument must be the result of
     * an Expression). A raw Number, or a raw numeric string (bcmath's own currency, kept as-is rather
     * than round-tripped through a PHP float), is bridged through BcNumberLiteral; a raw int|float
     * goes through the shared WrapsRawValues, same as every other facade (Arithmetic, Math, ...) does
     * for its operands.
     */
    private static function wrapRawScalar(Number|int|float|string|Expression $value): NumberValue
    {
        if ($value instanceof Number) {
            return new BcNumberLiteral($value);
        }

        if (is_string($value)) {
            assert(is_numeric($value));

            return new BcNumberLiteral(new Number($value));
        }

        $wrapped = $value instanceof Expression ? $value : self::wrap($value);
        assert($wrapped instanceof NumberValue);

        return $wrapped;
    }

    private static function wrapScale(?int $scale): ?IntegerValue
    {
        return $scale === null ? null : new IntLiteral($scale);
    }

    /**
     * Same as decorateNumber()/decorateBoolean()/decorateString() (Foundation\Support\DecoratesNodes), but
     * re-exposed as PrecisionNumberValue instead of the plain NumberValue those helpers guarantee — a
     * user-supplied decorator only implements the generic Decorator contract, which would otherwise
     * lose bcValue() for the next BcMath node in the chain. This stays local to BcMath rather than
     * living in the shared DecoratesNodes trait, since PrecisionNumberValue is itself BcMath-only.
     */
    private static function decoratePrecisionNumber(PrecisionNumberValue $node, PipelineConfig $config): PrecisionNumberValue
    {
        $decorated = self::decorateNumber($node, $config);

        return $decorated instanceof PrecisionNumberValue ? $decorated : new NumberValueToPrecisionAdapter($decorated);
    }
}
