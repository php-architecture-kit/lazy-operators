<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation;

use PhpArchitecture\LazyOperators\Foundation\Arithmetic\AdditionOperator;
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
use PhpArchitecture\LazyOperators\Foundation\Conditional\IfElseOperator;
use PhpArchitecture\LazyOperators\Foundation\Conditional\SwitchCaseOperator;
use PhpArchitecture\LazyOperators\Foundation\Custom\CallbackOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\AndOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\NotOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\OrOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\XorOperator;
use PhpArchitecture\LazyOperators\Foundation\Static\Value;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Guards the persistence identity of every Expression node: each concrete class must declare
 * its own KEY/UID/VERSION (not inherit them), KEY and UID must be unique across the whole set,
 * and UID must be a real UUIDv4.
 */
final class ExpressionConstantsTest extends TestCase
{
    private const CLASSES = [
        AdditionOperator::class,
        SubtractionOperator::class,
        MultiplicationOperator::class,
        DivisionOperator::class,
        ModuloOperator::class,
        ExponentiationOperator::class,
        SpaceshipOperator::class,
        EqualOperator::class,
        NotEqualOperator::class,
        IdenticalOperator::class,
        NotIdenticalOperator::class,
        GreaterThanOperator::class,
        GreaterThanOrEqualOperator::class,
        LessThanOperator::class,
        LessThanOrEqualOperator::class,
        IfElseOperator::class,
        SwitchCaseOperator::class,
        CallbackOperator::class,
        AndOperator::class,
        OrOperator::class,
        XorOperator::class,
        NotOperator::class,
        Value::class,
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
}
