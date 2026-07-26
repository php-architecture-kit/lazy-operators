<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification\IsFiniteFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification\IsInfiniteFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification\IsNanFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\BaseConvertFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\BinDecFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\DecBinFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\DecHexFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\DecOctFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\HexDecFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\OctDecFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\ExpFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\Expm1Function;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\HypotFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\Log10Function;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\Log1pFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\LogFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\PowFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\SqrtFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\AbsFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\FdivFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\FmodFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\IntdivFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\MaxFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\MinFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\GetRandMaxFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\LcgValueFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\MtGetRandMaxFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\MtRandFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\MtSrandFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\RandFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\RandomIntFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\SrandFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding\CeilFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding\FloorFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding\RoundFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AcosFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AcoshFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AsinFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AsinhFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\Atan2Function;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AtanFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AtanhFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\CosFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\CoshFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\Deg2RadFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\PiFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\Rad2DegFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\SinFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\SinhFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\TanFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\TanhFunction;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;
use RoundingMode;

/**
 * Static factories for every function in PHP's Math book (https://www.php.net/manual/en/book.math.php),
 * one per native function. The constant-only surface of the book (M_PI, M_E, PHP_ROUND_*, NAN, INF, ...)
 * is intentionally not wrapped here: those are plain scalar constants, already usable as `new Value(M_PI)`.
 */
class Math
{
    use WrapsRawValues;
    use DecoratesNodes;

    // Rounding

    public static function ceil(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new CeilFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function floor(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new FloorFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    /**
     * @param 1|2|3|4|RoundingMode $mode
     */
    public static function round(
        int|float|Expression $value,
        int|Expression|null $precision = null,
        int|RoundingMode $mode = PHP_ROUND_HALF_UP,
        ?PipelineConfig $config = null,
    ): Expression {
        $config ??= new PipelineConfig();

        return self::decorate(
            new RoundFunction(
                self::wrap($value),
                $precision !== null ? self::decorate(self::wrap($precision), $config) : null,
                $mode,
            ),
            $config,
        );
    }

    // Trigonometry

    public static function sin(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new SinFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function cos(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new CosFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function tan(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new TanFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function asin(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new AsinFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function acos(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new AcosFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function atan(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new AtanFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function atan2(int|float|Expression $y, int|float|Expression $x, ?PipelineConfig $config = null): Expression
    {
        $config ??= new PipelineConfig();

        return self::decorate(
            new Atan2Function(self::decorate(self::wrap($y), $config), self::decorate(self::wrap($x), $config)),
            $config,
        );
    }

    public static function sinh(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new SinhFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function cosh(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new CoshFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function tanh(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new TanhFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function asinh(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new AsinhFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function acosh(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new AcoshFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function atanh(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new AtanhFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function deg2rad(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new Deg2RadFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function rad2deg(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new Rad2DegFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function pi(?PipelineConfig $config = null): Expression
    {
        return self::decorate(new PiFunction(), $config ?? new PipelineConfig());
    }

    // Exponential / logarithmic

    public static function exp(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new ExpFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function expm1(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new Expm1Function(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function log(
        int|float|Expression $value,
        int|float|Expression|null $base = null,
        ?PipelineConfig $config = null,
    ): Expression {
        $config ??= new PipelineConfig();

        return self::decorate(
            new LogFunction(
                self::wrap($value),
                $base !== null ? self::decorate(self::wrap($base), $config) : null,
            ),
            $config,
        );
    }

    public static function log10(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new Log10Function(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function log1p(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new Log1pFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function pow(int|float|Expression $base, int|float|Expression $exponent, ?PipelineConfig $config = null): Expression
    {
        $config ??= new PipelineConfig();

        return self::decorate(
            new PowFunction(self::decorate(self::wrap($base), $config), self::decorate(self::wrap($exponent), $config)),
            $config,
        );
    }

    public static function sqrt(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new SqrtFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function hypot(int|float|Expression $x, int|float|Expression $y, ?PipelineConfig $config = null): Expression
    {
        $config ??= new PipelineConfig();

        return self::decorate(
            new HypotFunction(self::decorate(self::wrap($x), $config), self::decorate(self::wrap($y), $config)),
            $config,
        );
    }

    // Numeric

    public static function abs(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new AbsFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function fmod(int|float|Expression $dividend, int|float|Expression $divisor, ?PipelineConfig $config = null): Expression
    {
        $config ??= new PipelineConfig();

        return self::decorate(
            new FmodFunction(self::decorate(self::wrap($dividend), $config), self::decorate(self::wrap($divisor), $config)),
            $config,
        );
    }

    public static function fdiv(int|float|Expression $dividend, int|float|Expression $divisor, ?PipelineConfig $config = null): Expression
    {
        $config ??= new PipelineConfig();

        return self::decorate(
            new FdivFunction(self::decorate(self::wrap($dividend), $config), self::decorate(self::wrap($divisor), $config)),
            $config,
        );
    }

    public static function intdiv(int|Expression $dividend, int|Expression $divisor, ?PipelineConfig $config = null): Expression
    {
        $config ??= new PipelineConfig();

        return self::decorate(
            new IntdivFunction(self::decorate(self::wrap($dividend), $config), self::decorate(self::wrap($divisor), $config)),
            $config,
        );
    }

    public static function max(int|float|Expression $first, int|float|Expression ...$rest): Expression
    {
        return self::variadicNumeric(MaxFunction::class, $first, ...$rest);
    }

    public static function min(int|float|Expression $first, int|float|Expression ...$rest): Expression
    {
        return self::variadicNumeric(MinFunction::class, $first, ...$rest);
    }

    /**
     * @param class-string<MaxFunction|MinFunction> $class
     */
    private static function variadicNumeric(string $class, int|float|Expression $first, int|float|Expression ...$rest): Expression
    {
        $config = new PipelineConfig();
        $values = array_map(
            static fn (int|float|Expression $value) => self::decorate(self::wrap($value), $config),
            [$first, ...$rest],
        );

        return self::decorate(new $class(...$values), $config);
    }

    // Base conversion

    public static function binDec(string|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new BinDecFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function decBin(int|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new DecBinFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function decHex(int|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new DecHexFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function hexDec(string|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new HexDecFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function decOct(int|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new DecOctFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function octDec(string|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new OctDecFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function baseConvert(
        string|Expression $value,
        int|Expression $fromBase,
        int|Expression $toBase,
        ?PipelineConfig $config = null,
    ): Expression {
        $config ??= new PipelineConfig();

        return self::decorate(
            new BaseConvertFunction(
                self::decorate(self::wrap($value), $config),
                self::decorate(self::wrap($fromBase), $config),
                self::decorate(self::wrap($toBase), $config),
            ),
            $config,
        );
    }

    // Random

    public static function rand(int|Expression|null $min = null, int|Expression|null $max = null, ?PipelineConfig $config = null): Expression
    {
        $config ??= new PipelineConfig();

        return self::decorate(
            new RandFunction(
                $min !== null ? self::decorate(self::wrap($min), $config) : null,
                $max !== null ? self::decorate(self::wrap($max), $config) : null,
            ),
            $config,
        );
    }

    public static function mtRand(int|Expression|null $min = null, int|Expression|null $max = null, ?PipelineConfig $config = null): Expression
    {
        $config ??= new PipelineConfig();

        return self::decorate(
            new MtRandFunction(
                $min !== null ? self::decorate(self::wrap($min), $config) : null,
                $max !== null ? self::decorate(self::wrap($max), $config) : null,
            ),
            $config,
        );
    }

    public static function randomInt(int|Expression $min, int|Expression $max, ?PipelineConfig $config = null): Expression
    {
        $config ??= new PipelineConfig();

        return self::decorate(
            new RandomIntFunction(self::decorate(self::wrap($min), $config), self::decorate(self::wrap($max), $config)),
            $config,
        );
    }

    public static function getRandMax(?PipelineConfig $config = null): Expression
    {
        return self::decorate(new GetRandMaxFunction(), $config ?? new PipelineConfig());
    }

    public static function mtGetRandMax(?PipelineConfig $config = null): Expression
    {
        return self::decorate(new MtGetRandMaxFunction(), $config ?? new PipelineConfig());
    }

    public static function lcgValue(?PipelineConfig $config = null): Expression
    {
        return self::decorate(new LcgValueFunction(), $config ?? new PipelineConfig());
    }

    public static function srand(int|Expression|null $seed = null, ?PipelineConfig $config = null): Expression
    {
        $config ??= new PipelineConfig();

        return self::decorate(
            new SrandFunction($seed !== null ? self::decorate(self::wrap($seed), $config) : null),
            $config,
        );
    }

    public static function mtSrand(
        int|Expression|null $seed = null,
        int $mode = MT_RAND_MT19937,
        ?PipelineConfig $config = null,
    ): Expression {
        $config ??= new PipelineConfig();

        return self::decorate(
            new MtSrandFunction($seed !== null ? self::decorate(self::wrap($seed), $config) : null, $mode),
            $config,
        );
    }

    // Classification

    public static function isFinite(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new IsFiniteFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function isInfinite(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new IsInfiniteFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }

    public static function isNan(int|float|Expression $value, ?PipelineConfig $config = null): Expression
    {
        return self::decorate(new IsNanFunction(self::wrap($value)), $config ?? new PipelineConfig());
    }
}
