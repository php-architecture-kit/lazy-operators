<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Range;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\EmptyRangeException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\InvalidRangeException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\OpenRangeException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range\DateTimeRangeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range\RangeAnyContainsFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range\RangeContainsFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range\RangeEndFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range\RangeIntersectionFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range\RangeOverlapsFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range\RangeStartFunction;
use PHPUnit\Framework\TestCase;

/**
 * The range nodes only evaluate their operands and delegate to DateTimeRange (see DateTimeRangeTest),
 * so one file covers them.
 */
final class RangeFunctionsTest extends TestCase
{
    public function testRangeBuildsAHalfOpenRange(): void
    {
        self::assertSame(
            '[2026-01-01T00:00:00+01:00, 2026-02-01T00:00:00+01:00)',
            (string) self::range('2026-01-01', '2026-02-01')(),
        );
        self::assertNull(self::range('2026-01-01')()->end);
    }

    public function testRangeFailsWhenTheEndIsNotAfterTheStart(): void
    {
        $this->expectException(InvalidRangeException::class);

        self::range('2026-02-01', '2026-01-01')();
    }

    public function testContains(): void
    {
        self::assertTrue((new RangeContainsFunction(self::range('2026-01-01', '2026-02-01'), self::at('2026-01-31')))());
        self::assertFalse((new RangeContainsFunction(self::range('2026-01-01', '2026-02-01'), self::at('2026-02-01')))());
    }

    public function testOverlaps(): void
    {
        self::assertTrue((new RangeOverlapsFunction(self::range('2026-01-01', '2026-02-02'), self::range('2026-02-01')))());
        self::assertFalse((new RangeOverlapsFunction(self::range('2026-01-01', '2026-02-01'), self::range('2026-02-01')))());
    }

    public function testIntersection(): void
    {
        $intersection = new RangeIntersectionFunction(self::range('2026-01-01', '2026-03-01'), self::range('2026-02-01', '2026-04-01'));

        self::assertSame('[2026-02-01T00:00:00+01:00, 2026-03-01T00:00:00+01:00)', (string) $intersection());
    }

    public function testIntersectionOfDisjointRangesFails(): void
    {
        $this->expectException(EmptyRangeException::class);

        (new RangeIntersectionFunction(self::range('2026-01-01', '2026-02-01'), self::range('2026-03-01')))();
    }

    public function testStartAndEnd(): void
    {
        $range = self::range('2026-01-01', '2026-02-01');

        self::assertSame('2026-01-01', (new RangeStartFunction($range))()->format('Y-m-d'));
        self::assertSame('2026-02-01', (new RangeEndFunction($range))()->format('Y-m-d'));
    }

    public function testTheEndOfAnOpenRangeFails(): void
    {
        $this->expectException(OpenRangeException::class);

        (new RangeEndFunction(self::range('2026-01-01')))();
    }

    public function testAnyContainsChecksEveryRange(): void
    {
        $january = self::range('2026-01-01', '2026-02-01');
        $march = self::range('2026-03-01', '2026-04-01');

        self::assertTrue((new RangeAnyContainsFunction(self::at('2026-03-15'), $january, $march))());
        self::assertFalse((new RangeAnyContainsFunction(self::at('2026-02-15'), $january, $march))());
        self::assertTrue((new RangeAnyContainsFunction(self::at('2026-01-15'), $january))());
    }

    private static function range(string $start, ?string $end = null): DateTimeRangeFunction
    {
        return new DateTimeRangeFunction(self::at($start), $end === null ? null : self::at($end));
    }

    private static function at(string $date): DateTimeLiteral
    {
        return new DateTimeLiteral($date . 'T00:00:00+01:00', 'Europe/Warsaw');
    }
}
