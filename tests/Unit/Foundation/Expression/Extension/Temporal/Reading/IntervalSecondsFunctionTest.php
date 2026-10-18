<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Reading;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading\IntervalSecondsFunction;
use PHPUnit\Framework\TestCase;

final class IntervalSecondsFunctionTest extends TestCase
{
    public function testADayWithAClockChangeCountsItsRealLength(): void
    {
        $seconds = new IntervalSecondsFunction(
            new DateTimeLiteral('2026-10-24T12:00:00+02:00', 'Europe/Warsaw'),
            new DateTimeLiteral('2026-10-25T12:00:00+01:00', 'Europe/Warsaw'),
        );

        self::assertSame(90000, $seconds());
    }

    public function testAnEarlierEndIsNegative(): void
    {
        $seconds = new IntervalSecondsFunction(
            new DateTimeLiteral('2026-10-17T10:30:00Z', 'UTC'),
            new DateTimeLiteral('2026-10-17T10:00:00Z', 'UTC'),
        );

        self::assertSame(-1800, $seconds());
    }
}
