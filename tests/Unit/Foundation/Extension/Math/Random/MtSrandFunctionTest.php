<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\MtSrandFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;
use PHPUnit\Framework\TestCase;

final class MtSrandFunctionTest extends TestCase
{
    public function testHasNoReturnValue(): void
    {
        $function = new MtSrandFunction(new NumericSpyExpression(42));

        self::assertNull($function());
    }

    public function testSeedingWithTheSameSeedProducesTheSameSequence(): void
    {
        (new MtSrandFunction(new NumericSpyExpression(42)))();
        $first = mt_rand();

        (new MtSrandFunction(new NumericSpyExpression(42)))();
        $second = mt_rand();

        self::assertSame($first, $second);
    }

    public function testDefaultsToSeedZeroWhenOmitted(): void
    {
        (new MtSrandFunction())();
        $first = mt_rand();

        (new MtSrandFunction(new NumericSpyExpression(0)))();
        $second = mt_rand();

        self::assertSame($first, $second);
    }

    public function testAcceptsAnExplicitAlgorithmMode(): void
    {
        (new MtSrandFunction(new NumericSpyExpression(42), MT_RAND_PHP))();
        $first = mt_rand();

        (new MtSrandFunction(new NumericSpyExpression(42), MT_RAND_PHP))();
        $second = mt_rand();

        self::assertSame($first, $second);
    }
}
