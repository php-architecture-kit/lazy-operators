<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Literal;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\InvalidDateTimeException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\IntervalLiteral;
use PHPUnit\Framework\TestCase;

final class IntervalLiteralTest extends TestCase
{
    public function testEachInvocationReturnsAFreshInterval(): void
    {
        $literal = new IntervalLiteral('P1M');

        $first = $literal();
        $first->m = 5;

        self::assertSame(1, $literal()->m);
    }

    public function testAMalformedIntervalIsRejectedWhenBuilt(): void
    {
        $this->expectException(InvalidDateTimeException::class);
        $this->expectExceptionMessage('"P7X" is not a valid ISO 8601 interval');

        new IntervalLiteral('P7X');
    }
}
