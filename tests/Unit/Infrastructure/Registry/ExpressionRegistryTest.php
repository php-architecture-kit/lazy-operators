<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Infrastructure\Registry;

use PhpArchitecture\LazyOperators\Application\Registry\Entry\Argument\CallbackArgument;
use PhpArchitecture\LazyOperators\Application\Registry\Entry\Argument\EnumArgument;
use PhpArchitecture\LazyOperators\Application\Registry\Entry\ExpressionArgument;
use PhpArchitecture\LazyOperators\Application\Registry\Entry\ExpressionAttributes;
use PhpArchitecture\LazyOperators\Application\Registry\Entry\ExpressionEntry;
use PhpArchitecture\LazyOperators\Foundation\Custom\CallbackOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Allocation\AllocationFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding\RoundFunction;
use PhpArchitecture\LazyOperators\Foundation\Logical\AndOperator;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Infrastructure\Registry\ExpressionRegistry;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionParameter;
use RoundingMode;

final class ExpressionRegistryTest extends TestCase
{
    public function testRegisterCapturesKeyUidVersionFqcnAndType(): void
    {
        $registry = new ExpressionRegistry();
        $registry->register(AndOperator::class);

        $entries = $registry->getAll();

        self::assertCount(1, $entries);

        $entry = $entries[0];
        self::assertSame(AndOperator::KEY, $entry->key);
        self::assertSame(AndOperator::UID, $entry->uid);
        self::assertSame(AndOperator::VERSION, $entry->version);
        self::assertSame(AndOperator::class, $entry->fqcn);
        self::assertSame('BooleanValue', $entry->type);
    }

    public function testMoreSpecificTypeInterfaceWinsOverItsSupertype(): void
    {
        $registry = new ExpressionRegistry();
        $registry->register(IntLiteral::class);

        $entry = $registry->getAll()[0];

        self::assertSame('IntegerValue', $entry->type, 'IntLiteral implements IntegerValue, which extends NumberValue; the more specific one must win.');
    }

    public function testTypeFallsBackToExpressionWhenNoTypeInterfaceMatches(): void
    {
        $registry = new ExpressionRegistry();
        $registry->register(CallbackOperator::class);

        $entry = $registry->getAll()[0];

        self::assertSame('Expression', $entry->type);
    }

    public function testRegisterCapturesNameFormulaAndDescriptionAttributes(): void
    {
        $registry = new ExpressionRegistry();
        $registry->register(AndOperator::class);

        $attributes = $registry->getAll()[0]->attributes;

        self::assertInstanceOf(ExpressionAttributes::class, $attributes);
        self::assertSame('And', $attributes->name?->value);
        self::assertSame('f(left, right) = left AND right', $attributes->formula?->value);
        self::assertNotNull($attributes->description);
    }

    public function testRegisterCapturesConstructorArgumentNamesAndTypes(): void
    {
        $registry = new ExpressionRegistry();
        $registry->register(AndOperator::class);

        $arguments = $registry->getAll()[0]->arguments;

        self::assertCount(2, $arguments);
        self::assertSame('left', $arguments[0]->name);
        self::assertSame('right', $arguments[1]->name);
        self::assertStringContainsString('BooleanValue', $arguments[0]->type);
    }

    public function testEnumConstructorParameterProducesAnEnumArgumentWithItsCaseNames(): void
    {
        $registry = new ExpressionRegistry();
        $registry->register(AllocationFunction::class);

        $arguments = $registry->getAll()[0]->arguments;
        $remainderTarget = array_values(array_filter(
            $arguments,
            static fn ($argument): bool => $argument->name === 'remainderTarget',
        ))[0];

        self::assertInstanceOf(EnumArgument::class, $remainderTarget);
        self::assertSame(['First', 'Largest', 'Smallest', 'Last'], $remainderTarget->options);
    }

    public function testEnumWithinAUnionTypeIsStillDetectedAsAnEnumArgument(): void
    {
        $registry = new ExpressionRegistry();
        $registry->register(RoundFunction::class);

        $arguments = $registry->getAll()[0]->arguments;
        $mode = array_values(array_filter(
            $arguments,
            static fn ($argument): bool => $argument->name === 'mode',
        ))[0];

        self::assertInstanceOf(EnumArgument::class, $mode);
        self::assertSame(
            array_map(static fn (RoundingMode $case): string => $case->name, RoundingMode::cases()),
            $mode->options,
        );
    }

    public function testClosureConstructorParameterProducesACallbackArgument(): void
    {
        $registry = new ExpressionRegistry();
        $registry->register(CallbackOperator::class);

        $arguments = $registry->getAll()[0]->arguments;
        $callback = array_values(array_filter(
            $arguments,
            static fn ($argument): bool => $argument->name === 'callback',
        ))[0];

        self::assertInstanceOf(CallbackArgument::class, $callback);
        self::assertSame('Closure', $callback->type);
    }

    public function testDefaultRegistersEveryConcreteExpressionShippedByTheLibrary(): void
    {
        $registry = ExpressionRegistry::default();

        $fqcns = array_map(static fn ($entry): string => $entry->fqcn, $registry->getAll());

        self::assertCount(88, $fqcns);
        self::assertContains(AndOperator::class, $fqcns);
        self::assertContains(IntLiteral::class, $fqcns);
        self::assertContains(CallbackOperator::class, $fqcns);
        self::assertContains(AllocationFunction::class, $fqcns);
        self::assertSame(array_unique($fqcns), $fqcns, 'no class should be registered twice');
    }

    public function testGetAllAccumulatesEveryRegisteredClass(): void
    {
        $registry = new ExpressionRegistry();
        $registry->register(AndOperator::class);
        $registry->register(IntLiteral::class);

        self::assertCount(2, $registry->getAll());
    }

    public function testUsersCanExtendTheRegistryAndOverrideAProtectedCreationMethod(): void
    {
        $registry = new class extends ExpressionRegistry {
            /**
             * @param ReflectionClass<Expression> $reflection
             */
            protected function createExpressionAttributes(ReflectionClass $reflection): ExpressionAttributes
            {
                $attributes = parent::createExpressionAttributes($reflection);

                return new ExpressionAttributes(
                    name: $attributes->name,
                    formula: $attributes->formula,
                    description: new Description('custom override'),
                );
            }
        };

        $registry->register(AndOperator::class);

        self::assertSame('custom override', $registry->getAll()[0]->attributes->description?->value);
    }

    public function testUsersCanOverrideArgumentCreationForCustomTypeLabelling(): void
    {
        $registry = new class extends ExpressionRegistry {
            protected function createArgument(ReflectionParameter $parameter): ExpressionArgument
            {
                return new ExpressionArgument(
                    strtoupper($parameter->getName()),
                    'custom-type',
                    null,
                );
            }
        };

        $registry->register(AndOperator::class);

        $arguments = $registry->getAll()[0]->arguments;

        self::assertSame('LEFT', $arguments[0]->name);
        self::assertSame('custom-type', $arguments[0]->type);
    }

    public function testEntryIsAnExpressionEntry(): void
    {
        $registry = new ExpressionRegistry();
        $registry->register(AndOperator::class);

        self::assertInstanceOf(ExpressionEntry::class, $registry->getAll()[0]);
    }
}
