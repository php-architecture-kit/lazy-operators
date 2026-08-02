<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation;

use PhpArchitecture\LazyOperators\Foundation\Arithmetic\AdditionOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\DivisionOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\ExponentiationOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\ModuloOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\MultiplicationOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\SubtractionOperator;
use PhpArchitecture\LazyOperators\Foundation\Cast\BooleanCast;
use PhpArchitecture\LazyOperators\Foundation\Cast\FloatCast;
use PhpArchitecture\LazyOperators\Foundation\Cast\IntegerCast;
use PhpArchitecture\LazyOperators\Foundation\Cast\StringCast;
use PhpArchitecture\LazyOperators\Foundation\Comparator\SpaceshipOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\EqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\GreaterThanOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\GreaterThanOrEqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\IdenticalOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\LessThanOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\LessThanOrEqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\NotEqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\NotIdenticalOperator;
use PhpArchitecture\LazyOperators\Foundation\Conditional\IfElseOperator;
use PhpArchitecture\LazyOperators\Foundation\Conditional\SwitchCaseOperator;
use PhpArchitecture\LazyOperators\Foundation\Custom\CallbackOperator;
use PhpArchitecture\LazyOperators\Foundation\Extension\Allocation\AllocationFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Array\ArrayGetFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\List\Aggregate\ProductFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\List\Aggregate\SumFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcAddFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcDivFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcMulFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcSubFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Comparison\BcCompFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\BcNumberLiteral;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\NumberValueToPrecisionAdapter;
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
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\RandFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\RandomIntFunction;
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
use PhpArchitecture\LazyOperators\Foundation\Logical\AndOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\NotOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\OrOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\XorOperator;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Runtime\Port;
use PhpArchitecture\LazyOperators\Foundation\Static\ArrayLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\BoolLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\FloatLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\StringLiteral;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Guards the persistence identity and UI metadata of every Expression node: each concrete class
 * must declare its own KEY/UID/VERSION (not inherit them), KEY and UID must be unique across the
 * whole set, UID must be a real UUIDv4, and each class must carry exactly one #[Name], #[Formula],
 * #[Description] and #[Group] attribute with a non-empty value.
 */
final class ExpressionConstantsTest extends TestCase
{
    private const CLASSES = [
        AbsFunction::class,
        AcosFunction::class,
        AcoshFunction::class,
        AdditionOperator::class,
        AllocationFunction::class,
        AndOperator::class,
        ArrayGetFunction::class,
        ArrayLiteral::class,
        AsinFunction::class,
        AsinhFunction::class,
        Atan2Function::class,
        AtanFunction::class,
        AtanhFunction::class,
        BaseConvertFunction::class,
        BcAddFunction::class,
        BcCompFunction::class,
        BcDivFunction::class,
        BcMulFunction::class,
        BcSubFunction::class,
        BinDecFunction::class,
        BoolLiteral::class,
        BooleanCast::class,
        CallbackOperator::class,
        CeilFunction::class,
        CosFunction::class,
        CoshFunction::class,
        DecBinFunction::class,
        DecHexFunction::class,
        DecOctFunction::class,
        Deg2RadFunction::class,
        DivisionOperator::class,
        EqualOperator::class,
        ExpFunction::class,
        Expm1Function::class,
        ExponentiationOperator::class,
        FdivFunction::class,
        FloatCast::class,
        FloatLiteral::class,
        FloorFunction::class,
        FmodFunction::class,
        GetRandMaxFunction::class,
        GreaterThanOperator::class,
        GreaterThanOrEqualOperator::class,
        HexDecFunction::class,
        HypotFunction::class,
        IdenticalOperator::class,
        IfElseOperator::class,
        IntLiteral::class,
        IntdivFunction::class,
        IntegerCast::class,
        IsFiniteFunction::class,
        IsInfiniteFunction::class,
        IsNanFunction::class,
        LcgValueFunction::class,
        LessThanOperator::class,
        LessThanOrEqualOperator::class,
        Log10Function::class,
        Log1pFunction::class,
        LogFunction::class,
        MaxFunction::class,
        MinFunction::class,
        ModuloOperator::class,
        MtGetRandMaxFunction::class,
        MtRandFunction::class,
        MultiplicationOperator::class,
        NotEqualOperator::class,
        NotIdenticalOperator::class,
        NotOperator::class,
        OctDecFunction::class,
        OrOperator::class,
        PiFunction::class,
        Port::class,
        PowFunction::class,
        ProductFunction::class,
        Rad2DegFunction::class,
        RandFunction::class,
        RandomIntFunction::class,
        RoundFunction::class,
        SinFunction::class,
        SinhFunction::class,
        SpaceshipOperator::class,
        SqrtFunction::class,
        StringCast::class,
        StringLiteral::class,
        SubtractionOperator::class,
        SumFunction::class,
        SwitchCaseOperator::class,
        TanFunction::class,
        TanhFunction::class,
        XorOperator::class,
    ];

    public function testEachClassDeclaresItsOwnConstants(): void
    {
        foreach (self::CLASSES as $class) {
            $reflection = new ReflectionClass($class);

            foreach (['KEY', 'UID', 'VERSION'] as $constantName) {
                $constant = $reflection->getReflectionConstant($constantName);

                self::assertNotFalse($constant, "{$class} is missing constant {$constantName}");
                self::assertSame(
                    $class,
                    $constant->getDeclaringClass()->getName(),
                    "{$class} does not declare its own {$constantName} (it is inherited)",
                );
            }
        }
    }

    public function testKeysAreUniqueAcrossAllClasses(): void
    {
        $keys = array_map(static fn (string $class): string => $class::KEY, self::CLASSES);

        self::assertSame(array_unique($keys), $keys);
    }

    public function testUidsAreUniqueAcrossAllClasses(): void
    {
        $uids = array_map(static fn (string $class): string => $class::UID, self::CLASSES);

        self::assertSame(array_unique($uids), $uids);
    }

    public function testUidsAreValidUuidV4Strings(): void
    {
        foreach (self::CLASSES as $class) {
            self::assertMatchesRegularExpression(
                '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
                $class::UID,
                "{$class}::UID is not a valid UUIDv4",
            );
        }
    }

    public function testEachClassHasExactlyOneOfEachMetaAttribute(): void
    {
        foreach (self::CLASSES as $class) {
            $reflection = new ReflectionClass($class);

            foreach ([Name::class, Formula::class, Description::class, Group::class] as $attributeClass) {
                $attributes = $reflection->getAttributes($attributeClass);

                self::assertCount(1, $attributes, "{$class} must have exactly one #[{$attributeClass}] attribute");
                self::assertNotSame('', $attributes[0]->newInstance()->value, "{$class}'s #[{$attributeClass}] value must not be empty");
            }
        }
    }
}
