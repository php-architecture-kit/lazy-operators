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
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\PrecisionNumberAdapter;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;
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
            new BcAddFunction(self::wrapRawScalar($left), self::wrapRawScalar($right), $scale),
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
            new BcSubFunction(self::wrapRawScalar($left), self::wrapRawScalar($right), $scale),
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
            new BcMulFunction(self::wrapRawScalar($left), self::wrapRawScalar($right), $scale),
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
            new BcDivFunction(self::wrapRawScalar($dividend), self::wrapRawScalar($divisor), $scale),
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
            new BcCompFunction(self::wrapRawScalar($left), self::wrapRawScalar($right), $scale),
            $config ?? new PipelineConfig(),
        );
    }

    /**
     * BcAddFunction et al. only accept Number|Expression (see PrecisionNumberValue::normalize()) — a raw
     * int|float|string given directly to the facade still needs wrapping via WrapsRawValues first, same
     * as every other facade (Arithmetic, Math, ...) does for its own operands.
     */
    private static function wrapRawScalar(Number|int|float|string|Expression $value): Number|Expression
    {
        return $value instanceof Number || $value instanceof Expression ? $value : self::wrap($value);
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
        $decorated = self::decorate($node, $config);

        return $decorated instanceof PrecisionNumberValue ? $decorated : new PrecisionNumberAdapter($decorated);
    }
}
