<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Cast;

use PhpArchitecture\LazyOperators\Foundation\Expression\Cast\IntegerCast;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class IntegerCastTest extends TestCase
{
    public function testCoercesAFloatResultIntoAnInt(): void
    {
        $cast = new IntegerCast(new SpyExpression(4.7));

        self::assertSame(4, $cast());
    }

    public function testCoercesANumericStringResultIntoAnInt(): void
    {
        $cast = new IntegerCast(new SpyExpression('42'));

        self::assertSame(42, $cast());
    }

    public function testAcceptsAnyExpressionRegardlessOfItsProducedType(): void
    {
        $spy = new SpyExpression('7');
        $cast = new IntegerCast($spy);

        $cast();

        self::assertSame(1, $spy->invocations);
    }
}
