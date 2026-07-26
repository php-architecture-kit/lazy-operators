<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation;

use PhpArchitecture\LazyOperators\Foundation\Arithmetic\Arithmetic;
use PhpArchitecture\LazyOperators\Foundation\Conditional\Conditional;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Static\Value;
use PhpArchitecture\LazyOperators\Tests\Support\RecordingExpression;
use PHPUnit\Framework\TestCase;

final class PipelineConfigTest extends TestCase
{
    protected function setUp(): void
    {
        RecordingExpression::reset();
    }

    public function testDecoratorDefaultsToNull(): void
    {
        self::assertNull((new PipelineConfig())->decorator);
    }

    public function testDecoratorHoldsTheGivenPrototype(): void
    {
        $decorator = new RecordingExpression(new Value(0));

        self::assertSame($decorator, (new PipelineConfig($decorator))->decorator);
    }

    public function testArithmeticDecoratesEveryNodeInEvaluationOrder(): void
    {
        $config = new PipelineConfig(new RecordingExpression(new Value(0)));

        $expr = Arithmetic::of(2, $config)->add(3)->multiply(10)->build();

        self::assertSame(50, $expr());
        self::assertSame([2, 3, 5, 10, 50], RecordingExpression::$log);
    }

    public function testIfDecoratesConditionTakenBranchAndTopNode(): void
    {
        $config = new PipelineConfig(new RecordingExpression(new Value(0)));

        $expr = Conditional::if(true, $config)->then('yes')->else('no')->build();

        self::assertSame('yes', $expr());
        self::assertSame([true, 'yes', 'yes'], RecordingExpression::$log);
    }

    public function testSwitchDecoratesSubjectEvaluatedCasesMatchedValueAndTopNode(): void
    {
        $config = new PipelineConfig(new RecordingExpression(new Value(0)));

        $expr = Conditional::switch(2, $config)
            ->case(1, 'a')
            ->case(2, 'b')
            ->default('fallback')
            ->build();

        self::assertSame('b', $expr());
        self::assertSame([2, 1, 2, 'b', 'b'], RecordingExpression::$log);
    }
}
