<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Cast;

use PhpArchitecture\LazyOperators\Foundation\Expression\Cast\ArrayCast;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class ArrayCastTest extends TestCase
{
    public function testAnArrayResultIsReturnedUnchanged(): void
    {
        self::assertSame(['2026-12-24', '2026-12-25'], (new ArrayCast(new SpyExpression(['2026-12-24', '2026-12-25'])))());
    }

    public function testAScalarResultBecomesAOneElementList(): void
    {
        self::assertSame(['2026-12-24'], (new ArrayCast(new SpyExpression('2026-12-24')))());
    }

    public function testNullBecomesAnEmptyArray(): void
    {
        self::assertSame([], (new ArrayCast(new SpyExpression(null)))());
    }
}
