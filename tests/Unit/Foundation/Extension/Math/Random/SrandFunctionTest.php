<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\SrandFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class SrandFunctionTest extends TestCase
{
    public function testHasNoReturnValue(): void
    {
        $function = new SrandFunction(new SpyExpression(42));

        self::assertNull($function());
    }

    public function testSeedingWithTheSameSeedProducesTheSameSequence(): void
    {
        (new SrandFunction(new SpyExpression(42)))();
        $first = rand();

        (new SrandFunction(new SpyExpression(42)))();
        $second = rand();

        self::assertSame($first, $second);
    }

    public function testDefaultsToSeedZeroWhenOmitted(): void
    {
        (new SrandFunction())();
        $first = rand();

        (new SrandFunction(new SpyExpression(0)))();
        $second = rand();

        self::assertSame($first, $second);
    }
}
