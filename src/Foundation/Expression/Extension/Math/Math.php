<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Classification\IsFiniteFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Classification\IsInfiniteFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Classification\IsNanFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Conversion\BaseConvertFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Conversion\BinDecFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Conversion\DecBinFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Conversion\DecHexFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Conversion\DecOctFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Conversion\HexDecFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Conversion\OctDecFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Exponential\ExpFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Exponential\Expm1Function;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Exponential\HypotFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Exponential\Log10Function;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Exponential\Log1pFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Exponential\LogFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Exponential\PowFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Exponential\SqrtFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Numeric\AbsFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Numeric\FdivFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Numeric\FmodFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Numeric\IntdivFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Numeric\MaxFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Numeric\MinFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Random\GetRandMaxFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Random\LcgValueFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Random\MtGetRandMaxFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Random\MtRandFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Random\RandFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Random\RandomIntFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Rounding\CeilFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Rounding\FloorFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Rounding\RoundFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\AcosFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\AcoshFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\AsinFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\AsinhFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\Atan2Function;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\AtanFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\AtanhFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\CosFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\CoshFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\Deg2RadFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\PiFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\Rad2DegFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\SinFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\SinhFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\TanFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\TanhFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\WrapsRawValues;
use RoundingMode;

/**
 * Static factories for every function in PHP's Math book (https://www.php.net/manual/en/book.math.php),
 * one per native function. The constant-only surface of the book (M_PI, M_E, PHP_ROUND_*, NAN, INF, ...)
 * is intentionally not wrapped here: those are plain scalar constants, already usable as `new FloatLiteral(M_PI)`.
 */
class Math
{
    use WrapsRawValues;
    use DecoratesNodes;

    // Rounding

    public static function ceil(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new CeilFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function floor(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new FloorFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    /**
     * @param 1|2|3|4|RoundingMode $mode
     */
    public static function round(
        int|float|NumberValue $value,
        int|NumberValue|null $precision = null,
        int|RoundingMode $mode = PHP_ROUND_HALF_UP,
        ?PipelineConfig $config = null,
    ): NumberValue {
        $config ??= new PipelineConfig();

        return self::decorateNumber(
            new RoundFunction(
                self::wrapAs(NumberValue::class, $value),
                $precision !== null ? self::decorateNumeric($precision, $config) : null,
                $mode,
            ),
            $config,
        );
    }

    // Trigonometry

    public static function sin(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new SinFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function cos(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new CosFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function tan(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new TanFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function asin(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new AsinFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function acos(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new AcosFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function atan(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new AtanFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function atan2(int|float|NumberValue $y, int|float|NumberValue $x, ?PipelineConfig $config = null): NumberValue
    {
        $config ??= new PipelineConfig();

        return self::decorateNumber(
            new Atan2Function(self::decorateNumeric($y, $config), self::decorateNumeric($x, $config)),
            $config,
        );
    }

    public static function sinh(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new SinhFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function cosh(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new CoshFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function tanh(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new TanhFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function asinh(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new AsinhFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function acosh(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new AcoshFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function atanh(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new AtanhFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function deg2rad(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new Deg2RadFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function rad2deg(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new Rad2DegFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function pi(?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new PiFunction(), $config ?? new PipelineConfig());
    }

    // Exponential / logarithmic

    public static function exp(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new ExpFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function expm1(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new Expm1Function(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function log(
        int|float|NumberValue $value,
        int|float|NumberValue|null $base = null,
        ?PipelineConfig $config = null,
    ): NumberValue {
        $config ??= new PipelineConfig();

        return self::decorateNumber(
            new LogFunction(
                self::wrapAs(NumberValue::class, $value),
                $base !== null ? self::decorateNumeric($base, $config) : null,
            ),
            $config,
        );
    }

    public static function log10(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new Log10Function(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function log1p(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new Log1pFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function pow(int|float|NumberValue $base, int|float|NumberValue $exponent, ?PipelineConfig $config = null): NumberValue
    {
        $config ??= new PipelineConfig();

        return self::decorateNumber(
            new PowFunction(self::decorateNumeric($base, $config), self::decorateNumeric($exponent, $config)),
            $config,
        );
    }

    public static function sqrt(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new SqrtFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function hypot(int|float|NumberValue $x, int|float|NumberValue $y, ?PipelineConfig $config = null): NumberValue
    {
        $config ??= new PipelineConfig();

        return self::decorateNumber(
            new HypotFunction(self::decorateNumeric($x, $config), self::decorateNumeric($y, $config)),
            $config,
        );
    }

    // Numeric

    public static function abs(int|float|NumberValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new AbsFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function fmod(int|float|NumberValue $dividend, int|float|NumberValue $divisor, ?PipelineConfig $config = null): NumberValue
    {
        $config ??= new PipelineConfig();

        return self::decorateNumber(
            new FmodFunction(self::decorateNumeric($dividend, $config), self::decorateNumeric($divisor, $config)),
            $config,
        );
    }

    public static function fdiv(int|float|NumberValue $dividend, int|float|NumberValue $divisor, ?PipelineConfig $config = null): NumberValue
    {
        $config ??= new PipelineConfig();

        return self::decorateNumber(
            new FdivFunction(self::decorateNumeric($dividend, $config), self::decorateNumeric($divisor, $config)),
            $config,
        );
    }

    public static function intdiv(int|NumberValue $dividend, int|NumberValue $divisor, ?PipelineConfig $config = null): NumberValue
    {
        $config ??= new PipelineConfig();

        return self::decorateNumber(
            new IntdivFunction(self::decorateNumeric($dividend, $config), self::decorateNumeric($divisor, $config)),
            $config,
        );
    }

    public static function max(int|float|NumberValue $first, int|float|NumberValue ...$rest): NumberValue
    {
        return self::variadicNumeric(MaxFunction::class, $first, ...$rest);
    }

    public static function min(int|float|NumberValue $first, int|float|NumberValue ...$rest): NumberValue
    {
        return self::variadicNumeric(MinFunction::class, $first, ...$rest);
    }

    /**
     * @param class-string<MaxFunction|MinFunction> $class
     */
    private static function variadicNumeric(string $class, int|float|NumberValue $first, int|float|NumberValue ...$rest): NumberValue
    {
        $config = new PipelineConfig();
        $values = array_map(
            static fn (int|float|NumberValue $value) => self::decorateNumeric($value, $config),
            [$first, ...$rest],
        );

        return self::decorateNumber(new $class(...$values), $config);
    }

    // Base conversion

    public static function binDec(string|StringValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new BinDecFunction(self::wrapAs(StringValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function decBin(int|NumberValue $value, ?PipelineConfig $config = null): StringValue
    {
        return self::decorateString(new DecBinFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function decHex(int|NumberValue $value, ?PipelineConfig $config = null): StringValue
    {
        return self::decorateString(new DecHexFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function hexDec(string|StringValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new HexDecFunction(self::wrapAs(StringValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function decOct(int|NumberValue $value, ?PipelineConfig $config = null): StringValue
    {
        return self::decorateString(new DecOctFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function octDec(string|StringValue $value, ?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new OctDecFunction(self::wrapAs(StringValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function baseConvert(
        string|StringValue $value,
        int|NumberValue $fromBase,
        int|NumberValue $toBase,
        ?PipelineConfig $config = null,
    ): StringValue {
        $config ??= new PipelineConfig();

        return self::decorateString(
            new BaseConvertFunction(
                self::decorateString(self::wrapAs(StringValue::class, $value), $config),
                self::decorateNumeric($fromBase, $config),
                self::decorateNumeric($toBase, $config),
            ),
            $config,
        );
    }

    // Random

    public static function rand(int|NumberValue|null $min = null, int|NumberValue|null $max = null, ?PipelineConfig $config = null): NumberValue
    {
        $config ??= new PipelineConfig();

        return self::decorateNumber(
            new RandFunction(
                $min !== null ? self::decorateNumeric($min, $config) : null,
                $max !== null ? self::decorateNumeric($max, $config) : null,
            ),
            $config,
        );
    }

    public static function mtRand(int|NumberValue|null $min = null, int|NumberValue|null $max = null, ?PipelineConfig $config = null): NumberValue
    {
        $config ??= new PipelineConfig();

        return self::decorateNumber(
            new MtRandFunction(
                $min !== null ? self::decorateNumeric($min, $config) : null,
                $max !== null ? self::decorateNumeric($max, $config) : null,
            ),
            $config,
        );
    }

    public static function randomInt(int|NumberValue $min, int|NumberValue $max, ?PipelineConfig $config = null): NumberValue
    {
        $config ??= new PipelineConfig();

        return self::decorateNumber(
            new RandomIntFunction(self::decorateNumeric($min, $config), self::decorateNumeric($max, $config)),
            $config,
        );
    }

    public static function getRandMax(?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new GetRandMaxFunction(), $config ?? new PipelineConfig());
    }

    public static function mtGetRandMax(?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new MtGetRandMaxFunction(), $config ?? new PipelineConfig());
    }

    public static function lcgValue(?PipelineConfig $config = null): NumberValue
    {
        return self::decorateNumber(new LcgValueFunction(), $config ?? new PipelineConfig());
    }

    // Classification

    public static function isFinite(int|float|NumberValue $value, ?PipelineConfig $config = null): BooleanValue
    {
        return self::decorateBoolean(new IsFiniteFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function isInfinite(int|float|NumberValue $value, ?PipelineConfig $config = null): BooleanValue
    {
        return self::decorateBoolean(new IsInfiniteFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    public static function isNan(int|float|NumberValue $value, ?PipelineConfig $config = null): BooleanValue
    {
        return self::decorateBoolean(new IsNanFunction(self::wrapAs(NumberValue::class, $value)), $config ?? new PipelineConfig());
    }

    private static function decorateNumeric(int|float|NumberValue $value, PipelineConfig $config): NumberValue
    {
        return self::decorateNumber(self::wrapAs(NumberValue::class, $value), $config);
    }
}
