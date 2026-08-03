<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Custom;

use PhpArchitecture\LazyOperators\Foundation\Custom\CallbackOperator;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class CallbackOperatorTest extends TestCase
{
    public function testInvokesCallbackWithNoArguments(): void
    {
        $operator = new CallbackOperator(static fn (): string => 'called');

        self::assertSame('called', $operator());
    }

    public function testResolvesVariadicArgumentsBeforePassingThemToCallback(): void
    {
        $operator = new CallbackOperator(
            static fn (int $a, int $b): int => $a + $b,
            new SpyExpression(2),
            new SpyExpression(3),
        );

        self::assertSame(5, $operator());
    }

    public function testCallbackReceivesResolvedValuesInOrder(): void
    {
        $received = [];
        $operator = new CallbackOperator(
            static function (...$args) use (&$received): void {
                $received = $args;
            },
            new SpyExpression('first'),
            new SpyExpression('second'),
        );

        $operator();

        self::assertSame(['first', 'second'], $received);
    }
}
