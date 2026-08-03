<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Cast;

use PhpArchitecture\LazyOperators\Foundation\Cast\BooleanCast;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class BooleanCastTest extends TestCase
{
    public function testCoercesAZeroResultIntoFalse(): void
    {
        $cast = new BooleanCast(new SpyExpression(0));

        self::assertFalse($cast());
    }

    public function testCoercesANonEmptyStringResultIntoTrue(): void
    {
        $cast = new BooleanCast(new SpyExpression('non-empty'));

        self::assertTrue($cast());
    }
}
