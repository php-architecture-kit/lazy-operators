<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Reading;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading\DateTimeDiffFunction;
use PHPUnit\Framework\TestCase;

final class DateTimeDiffFunctionTest extends TestCase
{
    public function testItReturnsTheCalendarInterval(): void
    {
        $diff = (new DateTimeDiffFunction(
            new DateTimeLiteral('2026-01-15T10:00:00+01:00', 'Europe/Warsaw'),
            new DateTimeLiteral('2026-03-16T12:30:00+01:00', 'Europe/Warsaw'),
        ))();

        self::assertSame([2, 1, 2, 30, 0], [$diff->m, $diff->d, $diff->h, $diff->i, $diff->invert]);
        self::assertSame(60, $diff->days);
    }

    public function testAnEarlierEndIsNegative(): void
    {
        $diff = (new DateTimeDiffFunction(
            new DateTimeLiteral('2026-10-18T10:00:00+02:00', 'Europe/Warsaw'),
            new DateTimeLiteral('2026-10-17T10:00:00+02:00', 'Europe/Warsaw'),
        ))();

        self::assertSame(1, $diff->invert);
        self::assertSame(1, $diff->days);
    }
}
