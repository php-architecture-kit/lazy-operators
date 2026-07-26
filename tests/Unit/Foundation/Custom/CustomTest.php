<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Custom;

use PhpArchitecture\LazyOperators\Foundation\Custom\CallbackOperator;
use PhpArchitecture\LazyOperators\Foundation\Custom\Custom;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class CustomTest extends TestCase
{
    public function testCallbackReturnsACallbackOperator(): void
    {
        self::assertInstanceOf(CallbackOperator::class, Custom::callback(static fn (): string => 'called'));
    }

    public function testCallbackWithNoArguments(): void
    {
        $expr = Custom::callback(static fn (): string => 'called');

        self::assertSame('called', $expr());
    }

    public function testCallbackAutoWrapsRawArguments(): void
    {
        $expr = Custom::callback(static fn (int $a, int $b): int => $a + $b, 2, 3);

        self::assertSame(5, $expr());
    }

    public function testCallbackAcceptsExpressionArgumentsDirectly(): void
    {
        $argument = new SpyExpression(3);

        $expr = Custom::callback(static fn (int $a, int $b): int => $a + $b, 2, $argument);

        self::assertSame(5, $expr());
        self::assertSame(1, $argument->invocations);
    }
}
