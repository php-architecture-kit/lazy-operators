<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Conditional;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception\EvaluationFailedException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception\LazyOperatorsConditionalException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\FailOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\IfElseOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\BoolLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\StringLiteral;
use PhpArchitecture\LazyOperators\Tests\Support\StringSpyExpression;
use PHPUnit\Framework\TestCase;

final class FailOperatorTest extends TestCase
{
    public function testInvokingThrowsWithTheGivenMessage(): void
    {
        $fail = new FailOperator(new StringLiteral('quantity must be positive'));

        $this->expectException(EvaluationFailedException::class);
        $this->expectExceptionMessage('quantity must be positive');

        $fail();
    }

    public function testTheExceptionBelongsToTheConditionalFamily(): void
    {
        try {
            (new FailOperator(new StringLiteral('x')))();
            self::fail('FailOperator must throw.');
        } catch (LazyOperatorsConditionalException $exception) {
            self::assertInstanceOf(EvaluationFailedException::class, $exception);
        }
    }

    public function testTheMessageIsOnlyEvaluatedWhenTheBranchIsReached(): void
    {
        $message = new StringSpyExpression('never shown');

        $expr = new IfElseOperator(new BoolLiteral(true), new IntLiteral(1), new FailOperator($message));

        self::assertSame(1, $expr());
        self::assertSame(0, $message->invocations);
    }

    public function testFacadeAcceptsARawMessage(): void
    {
        $expr = Conditional::if(false)->then(1)->else(Conditional::fail('no valid result'))->build();

        $this->expectException(EvaluationFailedException::class);
        $this->expectExceptionMessage('no valid result');

        $expr();
    }
}
