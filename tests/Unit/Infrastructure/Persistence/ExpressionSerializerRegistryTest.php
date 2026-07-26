<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Infrastructure\Persistence;

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
use PhpArchitecture\LazyOperators\Foundation\Conditional\CaseOfSwitchCase;
use PhpArchitecture\LazyOperators\Foundation\Conditional\IfElseOperator;
use PhpArchitecture\LazyOperators\Foundation\Conditional\SwitchCaseOperator;
use PhpArchitecture\LazyOperators\Foundation\Custom\CallbackOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Logical\AndOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\NotOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\OrOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\XorOperator;
use PhpArchitecture\LazyOperators\Foundation\Static\Value;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\CallbackRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\IncompatibleExpressionVersionException;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\UnknownExpressionUidException;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\UnpersistableCallbackException;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\UnpersistableValueException;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\UnsupportedExpressionException;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializers;
use PhpArchitecture\LazyOperators\Tests\Support\RecordingExpression;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ExpressionSerializerRegistryTest extends TestCase
{
    /**
     * @return array<string, Expression>
     */
    private function sampleExpressions(): array
    {
        return [
            'addition' => new AdditionOperator(new Value(2), new Value(3)),
            'subtraction' => new SubtractionOperator(new Value(5), new Value(2)),
            'multiplication' => new MultiplicationOperator(new Value(4), new Value(3)),
            'division' => new DivisionOperator(new Value(10), new Value(2)),
            'modulo' => new ModuloOperator(new Value(10), new Value(3)),
            'exponentiation' => new ExponentiationOperator(new Value(2), new Value(3)),
            'equal' => new EqualOperator(new Value(1), new Value(1)),
            'not_equal' => new NotEqualOperator(new Value(1), new Value(2)),
            'identical' => new IdenticalOperator(new Value(1), new Value(1)),
            'not_identical' => new NotIdenticalOperator(new Value('1'), new Value(1)),
            'greater_than' => new GreaterThanOperator(new Value(2), new Value(1)),
            'greater_than_or_equal' => new GreaterThanOrEqualOperator(new Value(2), new Value(2)),
            'less_than' => new LessThanOperator(new Value(1), new Value(2)),
            'less_than_or_equal' => new LessThanOrEqualOperator(new Value(2), new Value(2)),
            'and' => new AndOperator(new Value(true), new Value(true)),
            'or' => new OrOperator(new Value(false), new Value(true)),
            'xor' => new XorOperator(new Value(true), new Value(false)),
            'not' => new NotOperator(new Value(true)),
            'spaceship' => new SpaceshipOperator(new Value(1), new Value(2)),
            'value' => new Value(42),
            'if_else' => new IfElseOperator(new Value(true), new Value('yes'), new Value('no')),
            'switch_case' => new SwitchCaseOperator(
                new Value(2),
                [
                    new CaseOfSwitchCase(new Value(1), new Value('a')),
                    new CaseOfSwitchCase(new Value(2), new Value('b')),
                ],
                new Value('fallback'),
            ),
            'nested' => new AdditionOperator(new AdditionOperator(new Value(1), new Value(2)), new Value(3)),
        ];
    }

    public function testEveryExpressionRoundTripsToTheSameResult(): void
    {
        $registry = ExpressionSerializers::default();

        foreach ($this->sampleExpressions() as $label => $expression) {
            $hydrated = $registry->deserialize($registry->serialize($expression));

            self::assertSame($expression(), $hydrated(), "Round-trip mismatch for \"{$label}\"");
        }
    }

    public function testSerializedShapeCarriesTheExpressionsOwnUidKeyClassAndVersion(): void
    {
        $registry = ExpressionSerializers::default();

        foreach ($this->sampleExpressions() as $label => $expression) {
            $class = $expression::class;
            $serialized = $registry->serialize($expression);

            self::assertSame($class::UID, $serialized['uid'], "uid mismatch for \"{$label}\"");
            self::assertSame($class::KEY, $serialized['key'], "key mismatch for \"{$label}\"");
            self::assertSame($class, $serialized['class'], "class mismatch for \"{$label}\"");
            self::assertSame($class::VERSION, $serialized['version'], "version mismatch for \"{$label}\"");
        }
    }

    public function testCallbackOperatorRoundTripsWhenItsClosureIsRegistered(): void
    {
        $callbacks = new CallbackRegistry();
        $callbacks->register('sum', static fn (int $a, int $b): int => $a + $b);
        $registry = ExpressionSerializers::default($callbacks);

        $expression = new CallbackOperator($callbacks->resolve('sum'), new Value(2), new Value(3));

        $hydrated = $registry->deserialize($registry->serialize($expression));

        self::assertSame(5, $hydrated());
    }

    public function testCallbackOperatorSerializationThrowsWhenClosureIsNotRegistered(): void
    {
        $expression = new CallbackOperator(static fn (): int => 1);

        $this->expectException(UnpersistableCallbackException::class);
        $this->expectExceptionMessage(
            'This closure was never registered via CallbackRegistry::register() and cannot be persisted.',
        );

        ExpressionSerializers::default()->serialize($expression);
    }

    public function testValueSerializationThrowsForNonJsonSafePayload(): void
    {
        $this->expectException(UnpersistableValueException::class);
        $this->expectExceptionMessage('Value wraps a "stdClass", which is not JSON-safe and cannot be persisted.');

        ExpressionSerializers::default()->serialize(new Value(new stdClass()));
    }

    public function testDeserializeThrowsForUnknownUid(): void
    {
        $this->expectException(UnknownExpressionUidException::class);
        $this->expectExceptionMessage('No serializer registered for Expression uid "not-a-registered-uid".');

        ExpressionSerializers::default()->deserialize([
            'uid' => 'not-a-registered-uid',
            'key' => 'value',
            'class' => Value::class,
            'version' => '1.0',
            'args' => [1],
        ]);
    }

    public function testDeserializeThrowsOnVersionMismatch(): void
    {
        $registry = ExpressionSerializers::default();
        $serialized = $registry->serialize(new Value(1));
        $serialized['version'] = '999.0';

        $this->expectException(IncompatibleExpressionVersionException::class);
        $this->expectExceptionMessage(sprintf(
            'Stored version "999.0" for Expression uid "%s" is incompatible with the currently registered version "%s".',
            Value::UID,
            Value::VERSION,
        ));

        $registry->deserialize($serialized);
    }

    public function testSerializeThrowsForAnUnregisteredExpressionClass(): void
    {
        $expression = new class implements Expression {
            public function __invoke(): mixed
            {
                return 'unregistered';
            }
        };

        $this->expectException(UnsupportedExpressionException::class);
        $this->expectExceptionMessage(sprintf('No serializer registered for Expression class "%s".', $expression::class));

        ExpressionSerializers::default()->serialize($expression);
    }

    public function testSerializedShapeIsIdenticalRegardlessOfDecoration(): void
    {
        $registry = ExpressionSerializers::default();

        $plain = new AdditionOperator(new Value(2), new Value(3));
        $decorated = new RecordingExpression(new AdditionOperator(new Value(2), new Value(3)));

        self::assertSame($registry->serialize($plain), $registry->serialize($decorated));
    }
}
