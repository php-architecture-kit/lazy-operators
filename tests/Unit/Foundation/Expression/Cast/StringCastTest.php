<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Cast;

use PhpArchitecture\LazyOperators\Foundation\Cast\StringCast;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class StringCastTest extends TestCase
{
    public function testCoercesAnIntResultIntoAString(): void
    {
        $cast = new StringCast(new SpyExpression(5));

        self::assertSame('5', $cast());
    }

    public function testCoercesABoolResultIntoAString(): void
    {
        $cast = new StringCast(new SpyExpression(true));

        self::assertSame('1', $cast());
    }
}
