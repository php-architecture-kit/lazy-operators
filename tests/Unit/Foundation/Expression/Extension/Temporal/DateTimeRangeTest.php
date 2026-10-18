<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DateTimeRange;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\EmptyRangeException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\InvalidRangeException;
use PHPUnit\Framework\TestCase;

final class DateTimeRangeTest extends TestCase
{
    public function testItContainsItsStartButNotItsEnd(): void
    {
        $range = self::range('2026-01-01', '2026-02-01');

        self::assertTrue($range->contains(self::at('2026-01-01')));
        self::assertTrue($range->contains(self::at('2026-01-31T23:59:59')));
        self::assertFalse($range->contains(self::at('2026-02-01')));
        self::assertFalse($range->contains(self::at('2025-12-31T23:59:59')));
    }

    public function testARangeWithoutAnEndLastsIndefinitely(): void
    {
        self::assertTrue(self::range('2026-01-01')->contains(self::at('2999-01-01')));
    }

    public function testTouchingRangesDoNotOverlap(): void
    {
        self::assertFalse(self::range('2026-01-01', '2026-02-01')->overlaps(self::range('2026-02-01', '2026-03-01')));
        self::assertTrue(self::range('2026-01-01', '2026-02-02')->overlaps(self::range('2026-02-01', '2026-03-01')));
        self::assertTrue(self::range('2026-01-01')->overlaps(self::range('2025-01-01', '2026-01-02')));
    }

    public function testTheIntersectionIsTheSharedPart(): void
    {
        self::assertSame(
            '[2026-02-01T00:00:00+00:00, 2026-02-15T00:00:00+00:00)',
            (string) self::range('2026-01-01', '2026-02-15')->intersection(self::range('2026-02-01')),
        );
        self::assertSame(
            '[2026-02-01T00:00:00+00:00, ∞)',
            (string) self::range('2026-01-01')->intersection(self::range('2026-02-01')),
        );
    }

    public function testDisjointRangesHaveNoIntersection(): void
    {
        $this->expectException(EmptyRangeException::class);

        self::range('2026-01-01', '2026-02-01')->intersection(self::range('2026-02-01', '2026-03-01'));
    }

    public function testTheEndMustBeAfterTheStart(): void
    {
        $this->expectException(InvalidRangeException::class);

        self::range('2026-01-01', '2026-01-01');
    }

    private static function range(string $start, ?string $end = null): DateTimeRange
    {
        return new DateTimeRange(self::at($start), $end === null ? null : self::at($end));
    }

    private static function at(string $value): DateTimeImmutable
    {
        return new DateTimeImmutable($value . (strlen($value) === 10 ? 'T00:00:00' : '') . 'Z');
    }
}
