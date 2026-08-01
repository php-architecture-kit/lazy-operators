<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Registry;

use Closure;
use PhpArchitecture\LazyOperators\Application\Registry\Entry\Argument\CallbackArgument;
use PhpArchitecture\LazyOperators\Application\Registry\Entry\Argument\CaseArgument;
use PhpArchitecture\LazyOperators\Application\Registry\Entry\Argument\EnumArgument;
use PhpArchitecture\LazyOperators\Application\Registry\Entry\ExpressionArgument;
use PhpArchitecture\LazyOperators\Application\Registry\Entry\ExpressionAttributes;
use PhpArchitecture\LazyOperators\Application\Registry\Entry\ExpressionEntry;
use PhpArchitecture\LazyOperators\Application\Registry\ExpressionRegistryInterface;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\AdditionOperator;
use PhpArchitecture\LazyOperators\Foundation\Cast\BooleanCast;
use PhpArchitecture\LazyOperators\Foundation\Cast\FloatCast;
use PhpArchitecture\LazyOperators\Foundation\Cast\IntegerCast;
use PhpArchitecture\LazyOperators\Foundation\Cast\StringCast;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\DivisionOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\ExponentiationOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\ModuloOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\MultiplicationOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\SubtractionOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparator\SpaceshipOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\EqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\GreaterThanOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\GreaterThanOrEqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\IdenticalOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\LessThanOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\LessThanOrEqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\NotEqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\NotIdenticalOperator;
use PhpArchitecture\LazyOperators\Foundation\Conditional\CaseOfSwitchCase;
use PhpArchitecture\LazyOperators\Foundation\Conditional\IfElseOperator;
use PhpArchitecture\LazyOperators\Foundation\Conditional\SwitchCaseOperator;
use PhpArchitecture\LazyOperators\Foundation\Custom\CallbackOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression;
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
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\ItemTypeOf;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Runtime\Port;
use PhpArchitecture\LazyOperators\Foundation\Static\ArrayLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\BoolLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\FloatLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\StringLiteral;
use PhpArchitecture\LazyOperators\Foundation\Type\ArrayValue;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Type\FloatValue;
use PhpArchitecture\LazyOperators\Foundation\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Type\ObjectValue;
use PhpArchitecture\LazyOperators\Foundation\Type\StringValue;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use UnitEnum;

class ExpressionRegistry implements ExpressionRegistryInterface
{
    /**
     * Ordered most specific first: a subtype (e.g. IntegerValue) must be checked
     * before the supertype it extends (NumberValue), or the supertype would always win.
     *
     * @var class-string[]
     */
    private const TYPE_INTERFACES = [
        IntegerValue::class,
        FloatValue::class,
        NumberValue::class,
        BooleanValue::class,
        StringValue::class,
        ArrayValue::class,
        ObjectValue::class,
    ];

    /**
     * Every concrete Expression node shipped by this library, in the same order as
     * ExpressionSerializers::default() (the other hand-maintained "every node" list).
     *
     * @var class-string<Expression>[]
     */
    private const LIBRARY_CLASSES = [
        AdditionOperator::class,
        SubtractionOperator::class,
        MultiplicationOperator::class,
        DivisionOperator::class,
        ModuloOperator::class,
        ExponentiationOperator::class,
        EqualOperator::class,
        NotEqualOperator::class,
        IdenticalOperator::class,
        NotIdenticalOperator::class,
        GreaterThanOperator::class,
        GreaterThanOrEqualOperator::class,
        LessThanOperator::class,
        LessThanOrEqualOperator::class,
        AndOperator::class,
        OrOperator::class,
        XorOperator::class,
        NotOperator::class,
        SpaceshipOperator::class,
        IfElseOperator::class,
        SwitchCaseOperator::class,
        CallbackOperator::class,
        IntLiteral::class,
        FloatLiteral::class,
        BoolLiteral::class,
        StringLiteral::class,
        ArrayLiteral::class,
        CeilFunction::class,
        FloorFunction::class,
        RoundFunction::class,
        SinFunction::class,
        CosFunction::class,
        TanFunction::class,
        AsinFunction::class,
        AcosFunction::class,
        AtanFunction::class,
        Atan2Function::class,
        SinhFunction::class,
        CoshFunction::class,
        TanhFunction::class,
        AsinhFunction::class,
        AcoshFunction::class,
        AtanhFunction::class,
        Deg2RadFunction::class,
        Rad2DegFunction::class,
        PiFunction::class,
        ExpFunction::class,
        Expm1Function::class,
        LogFunction::class,
        Log10Function::class,
        Log1pFunction::class,
        PowFunction::class,
        SqrtFunction::class,
        HypotFunction::class,
        AbsFunction::class,
        FmodFunction::class,
        FdivFunction::class,
        IntdivFunction::class,
        MaxFunction::class,
        MinFunction::class,
        BinDecFunction::class,
        DecBinFunction::class,
        DecHexFunction::class,
        HexDecFunction::class,
        DecOctFunction::class,
        OctDecFunction::class,
        BaseConvertFunction::class,
        RandFunction::class,
        MtRandFunction::class,
        RandomIntFunction::class,
        GetRandMaxFunction::class,
        MtGetRandMaxFunction::class,
        LcgValueFunction::class,
        IsFiniteFunction::class,
        IsInfiniteFunction::class,
        IsNanFunction::class,
        SumFunction::class,
        ProductFunction::class,
        ArrayGetFunction::class,
        AllocationFunction::class,
        BcAddFunction::class,
        BcSubFunction::class,
        BcMulFunction::class,
        BcDivFunction::class,
        BcCompFunction::class,
        BcNumberLiteral::class,
        NumberValueToPrecisionAdapter::class,
        IntegerCast::class,
        FloatCast::class,
        StringCast::class,
        BooleanCast::class,
        Port::class,
    ];

    /**
     * @var array<class-string<Expression>, ExpressionEntry>
     */
    private array $entries = [];

    /**
     * @return ExpressionEntry[]
     */
    public function getAll(): array
    {
        return array_values($this->entries);
    }

    public static function default(): self
    {
        $registry = new self();

        foreach (self::LIBRARY_CLASSES as $class) {
            $registry->register($class);
        }

        return $registry;
    }

    /**
     * @param class-string<Expression> $className
     */
    public function register(string $className): void
    {
        $reflection = new ReflectionClass($className);

        $this->entries[$className] = $this->createExpressionEntry(
            $className,
            $reflection,
            $this->createExpressionAttributes($reflection),
            $this->createArguments($reflection),
        );
    }

    /**
     * @param class-string<Expression> $className
     * @param ReflectionClass<Expression> $reflection
     * @param ExpressionArgument[] $arguments
     */
    protected function createExpressionEntry(
        string $className,
        ReflectionClass $reflection,
        ExpressionAttributes $attributes,
        array $arguments,
    ): ExpressionEntry {
        return new ExpressionEntry(
            key: $this->readStringConstant($reflection, 'KEY'),
            uid: $this->readStringConstant($reflection, 'UID'),
            version: $this->readStringConstant($reflection, 'VERSION'),
            fqcn: $className,
            type: $this->resolveType($reflection),
            attributes: $attributes,
            arguments: $arguments,
        );
    }

    /**
     * @param ReflectionClass<Expression> $reflection
     */
    protected function createExpressionAttributes(ReflectionClass $reflection): ExpressionAttributes
    {
        return new ExpressionAttributes(
            name: $this->readAttribute($reflection, Name::class),
            formula: $this->readAttribute($reflection, Formula::class),
            description: $this->readAttribute($reflection, Description::class),
            group: $this->readAttribute($reflection, Group::class),
        );
    }

    protected function createArgument(ReflectionParameter $parameter): ExpressionArgument
    {
        $name = $parameter->getName();
        $type = $this->shortenType((string) $parameter->getType());
        $itemType = $this->readAttribute($parameter, ItemTypeOf::class)?->value;
        $spread = $parameter->isVariadic();
        $optional = $parameter->isOptional();
        $defaultValue = $this->defaultValueDisplay($parameter);
        $description = $this->readAttribute($parameter, Description::class);

        if ($this->isCallbackType($parameter)) {
            return new CallbackArgument($name, $type, $itemType, $spread, $optional, $defaultValue, $description);
        }

        $enumClass = $this->resolveEnumType($parameter);

        if ($enumClass !== null) {
            return new EnumArgument(
                $name,
                $type,
                $itemType,
                $spread,
                $optional,
                $defaultValue,
                $description,
                array_map(static fn (UnitEnum $case): string => $case->name, $enumClass::cases()),
            );
        }

        // One-off, Enum-style special case: CaseOfSwitchCase is SwitchCaseOperator's only plain
        // value-object argument shape, not worth a generic "look inside this" mechanism for a
        // single occurrence.
        if ($itemType === (new ReflectionClass(CaseOfSwitchCase::class))->getShortName()) {
            return new CaseArgument(
                $name,
                $type,
                $itemType,
                $spread,
                $optional,
                $defaultValue,
                $description,
                $this->createArguments(new ReflectionClass(CaseOfSwitchCase::class)),
            );
        }

        return new ExpressionArgument($name, $type, $itemType, $spread, $optional, $defaultValue, $description);
    }

    /**
     * @template T of object
     * @param ReflectionClass<T> $reflection
     * @return ExpressionArgument[]
     */
    protected function createArguments(ReflectionClass $reflection): array
    {
        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return [];
        }

        return array_map(
            fn (ReflectionParameter $parameter): ExpressionArgument => $this->createArgument($parameter),
            $constructor->getParameters(),
        );
    }

    /**
     * @param ReflectionClass<Expression> $reflection
     */
    protected function resolveType(ReflectionClass $reflection): string
    {
        foreach (self::TYPE_INTERFACES as $interface) {
            if ($reflection->implementsInterface($interface)) {
                return (new ReflectionClass($interface))->getShortName();
            }
        }

        return (new ReflectionClass(Expression::class))->getShortName();
    }

    /**
     * @template T of object
     * @param ReflectionClass<Expression>|ReflectionParameter $subject
     * @param class-string<T> $attributeClass
     * @return T|null
     */
    private function readAttribute(ReflectionClass|ReflectionParameter $subject, string $attributeClass): ?object
    {
        $attributes = $subject->getAttributes($attributeClass);

        return $attributes === [] ? null : $attributes[0]->newInstance();
    }

    /**
     * @return class-string<UnitEnum>|null
     */
    private function resolveEnumType(ReflectionParameter $parameter): ?string
    {
        foreach ($this->namedTypeCandidates($parameter) as $candidate) {
            if (!$candidate->isBuiltin() && enum_exists($candidate->getName())) {
                /** @var class-string<UnitEnum> */
                return $candidate->getName();
            }
        }

        return null;
    }

    private function isCallbackType(ReflectionParameter $parameter): bool
    {
        foreach ($this->namedTypeCandidates($parameter) as $candidate) {
            if ($candidate->getName() === Closure::class) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return ReflectionNamedType[]
     */
    private function namedTypeCandidates(ReflectionParameter $parameter): array
    {
        $type = $parameter->getType();

        $candidates = match (true) {
            $type instanceof ReflectionUnionType => $type->getTypes(),
            $type instanceof ReflectionNamedType => [$type],
            default => [],
        };

        return array_values(array_filter(
            $candidates,
            static fn (mixed $candidate): bool => $candidate instanceof ReflectionNamedType,
        ));
    }

    /**
     * @param ReflectionClass<Expression> $reflection
     */
    private function readStringConstant(ReflectionClass $reflection, string $name): string
    {
        $value = $reflection->getConstant($name);
        assert(is_string($value));

        return $value;
    }

    /**
     * Strips namespaces from a reflection type string for UI display (e.g. the FQCN
     * "PhpArchitecture\LazyOperators\Foundation\Type\NumberValue" becomes "NumberValue"), while
     * leaving union separators ("|"), the nullable "?" prefix, and unqualified builtin names
     * (int, string, array, ...) untouched.
     */
    private function shortenType(string $type): string
    {
        return implode('|', array_map(
            static function (string $segment): string {
                $lastSeparator = strrpos($segment, '\\');

                return $lastSeparator === false ? $segment : substr($segment, $lastSeparator + 1);
            },
            explode('|', $type),
        ));
    }

    private function defaultValueDisplay(ReflectionParameter $parameter): ?string
    {
        if (!$parameter->isDefaultValueAvailable()) {
            return null;
        }

        $default = $parameter->getDefaultValue();

        return $default === null ? null : var_export($default, true);
    }
}
