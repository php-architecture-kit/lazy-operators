<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Application\Builder;

use PhpArchitecture\LazyOperators\Application\Builder\Exception\InvalidExpressionDefinitionException;
use PhpArchitecture\LazyOperators\Application\Builder\ExpressionBuilder;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception\EvaluationFailedException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Allocation\AllocationFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Allocation\AllocationRemainderTarget;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\FloatLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Infrastructure\Registry\ExpressionRegistry;
use PhpArchitecture\LazyOperators\Tests\Support\OptionalBeforeSpreadExpression;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ExpressionBuilderTest extends TestCase
{
    private ExpressionBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new ExpressionBuilder(ExpressionRegistry::default());
    }

    /**
     * @return array<string, mixed>
     */
    private static function int(int $value): array
    {
        return ['expression' => 'int_literal', 'arguments' => ['value' => $value]];
    }

    public function testBuildsANestedTreeAndEvaluatesIt(): void
    {
        $tree = $this->builder->build([
            'expression' => 'multiplication',
            'version' => '1.0',
            'arguments' => [
                'left' => ['expression' => 'addition', 'arguments' => ['left' => self::int(2), 'right' => self::int(3)]],
                'right' => self::int(4),
            ],
        ]);

        self::assertSame(20, $tree());
    }

    public function testSpreadArgumentTakesAListOfNodes(): void
    {
        $tree = $this->builder->build([
            'expression' => 'math_max',
            'arguments' => ['first' => self::int(3), 'rest' => [self::int(9), self::int(4)]],
        ]);

        self::assertSame(9, $tree());
    }

    public function testSpreadAfterNamedArgumentsAndAnEnumCaseMatchDirectConstruction(): void
    {
        $tree = $this->builder->build([
            'expression' => 'allocation',
            'arguments' => [
                'amount' => self::int(100),
                'precision' => self::int(0),
                'remainderTarget' => 'First',
                'firstShare' => self::int(1),
                'restShares' => [self::int(1), self::int(1)],
            ],
        ]);

        $direct = new AllocationFunction(
            new IntLiteral(100),
            new IntLiteral(0),
            AllocationRemainderTarget::First,
            new IntLiteral(1),
            new IntLiteral(1),
            new IntLiteral(1),
        );

        self::assertSame($direct(), $tree());
    }

    public function testOptionalArgumentsCanBeLeftOutAndEnumCasesAreReadByName(): void
    {
        $defaultMode = $this->builder->build([
            'expression' => 'math_round',
            'arguments' => ['value' => ['expression' => 'float_literal', 'arguments' => ['value' => 2.5]]],
        ]);
        $halfEven = $this->builder->build([
            'expression' => 'math_round',
            'arguments' => [
                'value' => ['expression' => 'float_literal', 'arguments' => ['value' => 2.5]],
                'mode' => 'HalfEven',
            ],
        ]);

        self::assertSame(3.0, $defaultMode());
        self::assertSame(2.0, $halfEven());
    }

    public function testSwitchCasesAreBuiltFromConditionValuePairs(): void
    {
        $tree = $this->builder->build([
            'expression' => 'switch_case',
            'arguments' => [
                'condition' => self::int(2),
                'cases' => [
                    ['condition' => self::int(1), 'value' => ['expression' => 'string_literal', 'arguments' => ['value' => 'one']]],
                    ['condition' => self::int(2), 'value' => ['expression' => 'string_literal', 'arguments' => ['value' => 'two']]],
                ],
            ],
        ]);

        self::assertSame('two', $tree());
    }

    public function testFailEndsTheEvaluationWithTheDefinedMessage(): void
    {
        $tree = $this->builder->build([
            'expression' => 'if_else',
            'arguments' => [
                'condition' => ['expression' => 'bool_literal', 'arguments' => ['value' => false]],
                'then' => self::int(1),
                'else' => [
                    'expression' => 'fail',
                    'arguments' => ['message' => ['expression' => 'string_literal', 'arguments' => ['value' => 'no valid result']]],
                ],
            ],
        ]);

        $this->expectException(EvaluationFailedException::class);
        $this->expectExceptionMessage('no valid result');

        $tree();
    }

    public function testPortsWithTheSameNameShareOneBindableInput(): void
    {
        // A Port is an untyped Expression; a cast makes it usable where a NumberValue is expected.
        $price = [
            'expression' => 'cast_float',
            'arguments' => ['value' => ['expression' => 'runtime_port', 'arguments' => ['name' => 'price']]],
        ];

        $tree = $this->builder->build([
            'expression' => 'addition',
            'arguments' => ['left' => $price, 'right' => $price],
        ]);

        self::assertSame(['price'], $tree->inputNames());

        $tree->bind('price', new FloatLiteral(1.5));

        self::assertSame(3.0, $tree());
    }

    public function testAnOptionalArgumentBeforeAGivenSpreadMustBeProvided(): void
    {
        $registry = new ExpressionRegistry();
        $registry->register(OptionalBeforeSpreadExpression::class);
        $registry->register(IntLiteral::class);
        $builder = new ExpressionBuilder($registry);

        $this->expectExceptionObject(InvalidExpressionDefinitionException::argumentBeforeSpreadOmitted(
            '$.arguments.first',
            'test_optional_before_spread',
            'first',
        ));

        $builder->build([
            'expression' => 'test_optional_before_spread',
            'arguments' => ['rest' => [self::int(1)]],
        ]);
    }

    /**
     * @return iterable<string, array{array<mixed>, string}>
     */
    public static function invalidDefinitions(): iterable
    {
        yield 'not a node' => [['value' => 1], '$: expected an expression definition object with an "expression" key.'];
        yield 'unknown expression' => [['expression' => 'nope'], '$: unknown expression "nope".'];
        yield 'incompatible version' => [
            ['expression' => 'int_literal', 'version' => '0.9', 'arguments' => ['value' => 1]],
            '$: "int_literal" definition has version 0.9, the library provides 1.0.',
        ];
        yield 'missing argument' => [
            ['expression' => 'addition', 'arguments' => ['left' => self::int(1)]],
            '$.arguments.right: "addition" requires argument "right".',
        ];
        yield 'unknown argument' => [
            ['expression' => 'int_literal', 'arguments' => ['value' => 1, 'extra' => 2]],
            '$.arguments.extra: "int_literal" has no argument "extra" (expected one of: value).',
        ];
        yield 'nested type mismatch' => [
            [
                'expression' => 'addition',
                'arguments' => [
                    'left' => self::int(1),
                    'right' => ['expression' => 'string_literal', 'arguments' => ['value' => 'x']],
                ],
            ],
            '$: Argument #2 ($right) must be of type NumberValue, StringLiteral given',
        ];
        yield 'literal type mismatch' => [
            ['expression' => 'int_literal', 'arguments' => ['value' => '5']],
            '$: Argument #1 ($value) must be of type int, string given',
        ];
        yield 'invalid enum case' => [
            [
                'expression' => 'allocation',
                'arguments' => [
                    'amount' => self::int(1),
                    'precision' => self::int(0),
                    'remainderTarget' => 'Middle',
                    'firstShare' => self::int(1),
                ],
            ],
            '$.arguments.remainderTarget: "Middle" is not one of: First, Largest, Smallest, Last.',
        ];
        yield 'spread not a list' => [
            ['expression' => 'math_max', 'arguments' => ['first' => self::int(1), 'rest' => self::int(2)]],
            '$.arguments.rest: expected a list.',
        ];
        yield 'closure argument' => [
            ['expression' => 'callback', 'arguments' => ['callback' => 'strlen']],
            '$.arguments.callback: Closure arguments cannot be described as data.',
        ];
        yield 'untyped port in a typed slot' => [
            [
                'expression' => 'addition',
                'arguments' => [
                    'left' => ['expression' => 'runtime_port', 'arguments' => ['name' => 'price']],
                    'right' => self::int(1),
                ],
            ],
            '$: Argument #1 ($left) must be of type NumberValue, Port given',
        ];
        yield 'empty port name' => [
            ['expression' => 'runtime_port', 'arguments' => ['name' => '']],
            '$.arguments.name: a port name must be a non-empty string.',
        ];
        yield 'a node rejecting its value when built' => [
            [
                'expression' => 'datetime_format',
                'arguments' => [
                    'datetime' => ['expression' => 'datetime_literal', 'arguments' => ['value' => '2026-10-17 10:00', 'timezone' => 'UTC']],
                    'format' => ['expression' => 'string_literal', 'arguments' => ['value' => 'Y-m-d']],
                ],
            ],
            '$.arguments.datetime: "2026-10-17 10:00" has no UTC offset or timezone',
        ];
        yield 'error deep in a case' => [
            [
                'expression' => 'switch_case',
                'arguments' => ['condition' => self::int(1), 'cases' => [['condition' => self::int(1)]]],
            ],
            '$.arguments.cases[0]: "case" requires argument "value".',
        ];
    }

    /**
     * @param array<mixed> $definition
     */
    #[DataProvider('invalidDefinitions')]
    public function testInvalidDefinitionsAreRejectedWithThePathToTheProblem(array $definition, string $message): void
    {
        $this->expectException(InvalidExpressionDefinitionException::class);
        $this->expectExceptionMessage($message);

        $this->builder->build($definition);
    }
}
