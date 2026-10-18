<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Literal;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\InvalidDateTimeException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PHPUnit\Framework\TestCase;

final class DateTimeLiteralTest extends TestCase
{
    public function testItReturnsTheInstantInTheTimezone(): void
    {
        $literal = new DateTimeLiteral('2026-12-31T23:30:00Z', 'Europe/Warsaw');

        self::assertSame('2027-01-01T00:30:00+01:00', $literal()->format(DATE_ATOM));
    }

    public function testAStringWithoutAnOffsetIsRejectedWhenBuilt(): void
    {
        $this->expectException(InvalidDateTimeException::class);

        new DateTimeLiteral('2026-12-31 23:30', 'Europe/Warsaw');
    }

    public function testAnUnknownTimezoneIsRejectedWhenBuilt(): void
    {
        $this->expectException(InvalidDateTimeException::class);

        new DateTimeLiteral('2026-12-31T23:30:00Z', 'Mars/Olympus');
    }
}
