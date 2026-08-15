<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Functional;

use function count;
use Monolog\Handler\TestHandler;
use Monolog\Level;
use Monolog\Logger;
use PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic\AdditionOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic\Arithmetic;
use PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic\MultiplicationOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparator\Comparator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparator\SpaceshipOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Conditional;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\IfElseOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\SwitchCaseOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Decorator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Logical\AndOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Logical\Logical;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Tests\Support\LoggerDecorator;
use PHPUnit\Framework\TestCase;

/**
 * "The most obvious decorator" one could plug in via ExpressionTreeConfig: log every evaluated
 * stage to a real PSR-3 logger (Monolog), naively, one DEBUG record per node, no batching or
 * filtering. These tests exercise that decorator through Monolog\Handler\TestHandler so the
 * package's actual behavior — not a hand-rolled test double — is what's under test.
 */
final class LoggerDecoratorTest extends TestCase
{
    private TestHandler $handler;
    private ExpressionTreeConfig $config;

    protected function setUp(): void
    {
        $this->handler = new TestHandler();

        $logger = new Logger('lazy-operators');
        $logger->pushHandler($this->handler);

        $this->config = ExpressionTreeConfig::decoratedBy(
            static fn (Expression $node): Decorator => new LoggerDecorator($node, $logger),
        );
    }

    public function testArithmeticChainLogsEveryStageAtDebugLevelInEvaluationOrder(): void
    {
        $expr = Arithmetic::of(2, $this->config)->add(3)->multiply(10)->build();

        self::assertSame(50, $expr());

        $records = $this->handler->getRecords();
        self::assertCount(5, $records);

        foreach ($records as $record) {
            self::assertSame(Level::Debug, $record->level);
            self::assertSame('lazy_operators.stage.evaluated', $record->message);
        }

        self::assertSame([2, 3, 5, 10, 50], array_column(array_map(static fn ($r) => $r->context, $records), 'result'));
        self::assertSame(
            [IntLiteral::class, IntLiteral::class, AdditionOperator::class, IntLiteral::class, MultiplicationOperator::class],
            array_column(array_map(static fn ($r) => $r->context, $records), 'node'),
        );
    }

    public function testLogicalChainLogsEveryStageAtDebugLevelInEvaluationOrder(): void
    {
        $expr = Logical::of(true, $this->config)->and(true)->build();

        self::assertTrue($expr());

        $records = $this->handler->getRecords();
        self::assertCount(3, $records);
        self::assertSame([true, true, true], array_column(array_map(static fn ($r) => $r->context, $records), 'result'));
        self::assertSame([AndOperator::class], [$records[2]->context['node']]);
    }

    public function testComparatorSpaceshipLogsOperandsAndResult(): void
    {
        $expr = Comparator::of(5, $this->config)->spaceship(3)->build();

        self::assertSame(1, $expr());

        $records = $this->handler->getRecords();
        self::assertCount(3, $records);
        self::assertSame([5, 3, 1], array_column(array_map(static fn ($r) => $r->context, $records), 'result'));
        self::assertSame(SpaceshipOperator::class, $records[2]->context['node']);
    }

    public function testConditionalIfLogsConditionTakenBranchAndTopNode(): void
    {
        $expr = Conditional::if(true, $this->config)->then('yes')->else('no')->build();

        self::assertSame('yes', $expr());

        $records = $this->handler->getRecords();
        self::assertCount(3, $records);
        self::assertSame([true, 'yes', 'yes'], array_column(array_map(static fn ($r) => $r->context, $records), 'result'));
        self::assertSame(IfElseOperator::class, $records[2]->context['node']);
    }

    public function testConditionalSwitchLogsSubjectEvaluatedCasesMatchedValueAndTopNode(): void
    {
        $expr = Conditional::switch(2, $this->config)
            ->case(1, 'a')
            ->case(2, 'b')
            ->default('fallback')
            ->build();

        self::assertSame('b', $expr());

        $records = $this->handler->getRecords();
        self::assertCount(5, $records);
        self::assertSame([2, 1, 2, 'b', 'b'], array_column(array_map(static fn ($r) => $r->context, $records), 'result'));
        self::assertSame(SwitchCaseOperator::class, $records[4]->context['node']);
    }

    public function testLoggerDecoratorDoesNotChangeAFloatOrStringChainsResult(): void
    {
        $number = Arithmetic::of(2.5, $this->config)->add(1.5)->build();
        $string = Comparator::of('same', $this->config)->build();

        self::assertSame(4.0, $number());
        self::assertSame('same', $string());

        self::assertTrue($this->handler->hasDebugThatContains('lazy_operators.stage.evaluated'));
        self::assertGreaterThanOrEqual(2, count($this->handler->getRecords()));
    }

    public function testWithoutADecoratorConfiguredNothingIsLoggedAtAll(): void
    {
        $config = new ExpressionTreeConfig();

        $expr = Arithmetic::of(2, $config)->add(3)->build();

        self::assertSame(5, $expr());
        self::assertCount(0, $this->handler->getRecords());
    }
}
