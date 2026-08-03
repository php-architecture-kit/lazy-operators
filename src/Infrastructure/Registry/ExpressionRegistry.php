<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Registry;

use Closure;
use PhpArchitecture\LazyOperators\Foundation\Registry\Entry\Argument\CallbackArgument;
use PhpArchitecture\LazyOperators\Foundation\Registry\Entry\Argument\CaseArgument;
use PhpArchitecture\LazyOperators\Foundation\Registry\Entry\Argument\EnumArgument;
use PhpArchitecture\LazyOperators\Foundation\Registry\Entry\ExpressionArgument;
use PhpArchitecture\LazyOperators\Foundation\Registry\Entry\ExpressionAttributes;
use PhpArchitecture\LazyOperators\Foundation\Registry\Entry\ExpressionEntry;
use PhpArchitecture\LazyOperators\Foundation\Registry\ExpressionRegistryInterface;
use PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic;
use PhpArchitecture\LazyOperators\Foundation\Expression\Cast;
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\CaseOfSwitchCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional;
use PhpArchitecture\LazyOperators\Foundation\Expression\Custom;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension;
use PhpArchitecture\LazyOperators\Foundation\Expression\Logical;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\ItemTypeOf;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Runtime\Port;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\ArrayLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\BoolLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\FloatLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\StringLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\ArrayValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\FloatValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\ObjectValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;
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
        Arithmetic\AdditionOperator::class,
        Arithmetic\SubtractionOperator::class,
        Arithmetic\MultiplicationOperator::class,
        Arithmetic\DivisionOperator::class,
        Arithmetic\ModuloOperator::class,
        Arithmetic\ExponentiationOperator::class,
        Cast\IntegerCast::class,
        Cast\FloatCast::class,
        Cast\StringCast::class,
        Cast\BooleanCast::class,
        Comparator\SpaceshipOperator::class,
        Comparison\EqualOperator::class,
        Comparison\NotEqualOperator::class,
        Comparison\IdenticalOperator::class,
        Comparison\NotIdenticalOperator::class,
        Comparison\GreaterThanOperator::class,
        Comparison\GreaterThanOrEqualOperator::class,
        Comparison\LessThanOperator::class,
        Comparison\LessThanOrEqualOperator::class,
        Logical\AndOperator::class,
        Logical\OrOperator::class,
        Logical\XorOperator::class,
        Logical\NotOperator::class,
        Conditional\IfElseOperator::class,
        Conditional\SwitchCaseOperator::class,
        Custom\CallbackOperator::class,
        IntLiteral::class,
        FloatLiteral::class,
        BoolLiteral::class,
        StringLiteral::class,
        ArrayLiteral::class,
        Extension\Math\Rounding\CeilFunction::class,
        Extension\Math\Rounding\FloorFunction::class,
        Extension\Math\Rounding\RoundFunction::class,
        Extension\Math\Trigonometry\SinFunction::class,
        Extension\Math\Trigonometry\CosFunction::class,
        Extension\Math\Trigonometry\TanFunction::class,
        Extension\Math\Trigonometry\AsinFunction::class,
        Extension\Math\Trigonometry\AcosFunction::class,
        Extension\Math\Trigonometry\AtanFunction::class,
        Extension\Math\Trigonometry\Atan2Function::class,
        Extension\Math\Trigonometry\SinhFunction::class,
        Extension\Math\Trigonometry\CoshFunction::class,
        Extension\Math\Trigonometry\TanhFunction::class,
        Extension\Math\Trigonometry\AsinhFunction::class,
        Extension\Math\Trigonometry\AcoshFunction::class,
        Extension\Math\Trigonometry\AtanhFunction::class,
        Extension\Math\Trigonometry\Deg2RadFunction::class,
        Extension\Math\Trigonometry\Rad2DegFunction::class,
        Extension\Math\Trigonometry\PiFunction::class,
        Extension\Math\Exponential\ExpFunction::class,
        Extension\Math\Exponential\Expm1Function::class,
        Extension\Math\Exponential\LogFunction::class,
        Extension\Math\Exponential\Log10Function::class,
        Extension\Math\Exponential\Log1pFunction::class,
        Extension\Math\Exponential\PowFunction::class,
        Extension\Math\Exponential\SqrtFunction::class,
        Extension\Math\Exponential\HypotFunction::class,
        Extension\Math\Numeric\AbsFunction::class,
        Extension\Math\Numeric\FmodFunction::class,
        Extension\Math\Numeric\FdivFunction::class,
        Extension\Math\Numeric\IntdivFunction::class,
        Extension\Math\Numeric\MaxFunction::class,
        Extension\Math\Numeric\MinFunction::class,
        Extension\Math\Conversion\BinDecFunction::class,
        Extension\Math\Conversion\DecBinFunction::class,
        Extension\Math\Conversion\DecHexFunction::class,
        Extension\Math\Conversion\HexDecFunction::class,
        Extension\Math\Conversion\DecOctFunction::class,
        Extension\Math\Conversion\OctDecFunction::class,
        Extension\Math\Conversion\BaseConvertFunction::class,
        Extension\Math\Random\RandFunction::class,
        Extension\Math\Random\MtRandFunction::class,
        Extension\Math\Random\RandomIntFunction::class,
        Extension\Math\Random\GetRandMaxFunction::class,
        Extension\Math\Random\MtGetRandMaxFunction::class,
        Extension\Math\Random\LcgValueFunction::class,
        Extension\Math\Classification\IsFiniteFunction::class,
        Extension\Math\Classification\IsInfiniteFunction::class,
        Extension\Math\Classification\IsNanFunction::class,
        Extension\List\Aggregate\SumFunction::class,
        Extension\List\Aggregate\ProductFunction::class,
        Extension\Array\ArrayGetFunction::class,
        Extension\Allocation\AllocationFunction::class,
        Extension\BcMath\Arithmetic\BcAddFunction::class,
        Extension\BcMath\Arithmetic\BcSubFunction::class,
        Extension\BcMath\Arithmetic\BcMulFunction::class,
        Extension\BcMath\Arithmetic\BcDivFunction::class,
        Extension\BcMath\Comparison\BcCompFunction::class,
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
                array_map(static fn(UnitEnum $case): string => $case->name, $enumClass::cases()),
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
            fn(ReflectionParameter $parameter): ExpressionArgument => $this->createArgument($parameter),
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
            static fn(mixed $candidate): bool => $candidate instanceof ReflectionNamedType,
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
     * "PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue" becomes "NumberValue"), while
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
