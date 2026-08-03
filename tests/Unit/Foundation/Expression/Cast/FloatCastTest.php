<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Cast;

use PhpArchitecture\LazyOperators\Foundation\Cast\FloatCast;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class FloatCastTest extends TestCase
{
    public function testCoercesAnIntResultIntoAFloat(): void
    {
        $cast = new FloatCast(new SpyExpression(3));

        self::assertSame(3.0, $cast());
    }

    public function testCoercesANumericStringResultIntoAFloat(): void
    {
        $cast = new FloatCast(new SpyExpression('1.5'));

        self::assertSame(1.5, $cast());
    }
}
