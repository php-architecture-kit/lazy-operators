<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Decorator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\CalendarUnit;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Cast\DateTimeCast;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DatePart;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\MonthOverflow;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Temporal;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\IntervalValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\RangeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Ports;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\StringLiteral;
use PhpArchitecture\LazyOperators\Tests\Support\ChannelDecorator;
use PHPUnit\Framework\TestCase;

final class TemporalTest extends TestCase
{
    protected function setUp(): void
    {
        ChannelDecorator::reset();
    }

    public function testRawValuesWithARawTimezoneBecomeALiteral(): void
    {
        self::assertInstanceOf(DateTimeLiteral::class, Temporal::datetime('2026-10-17T10:00:00Z', 'Europe/Warsaw'));
        self::assertSame(
            '2026-10-17T12:00:00+02:00',
            Temporal::datetime(new DateTimeImmutable('2026-10-17T10:00:00Z'), 'Europe/Warsaw')()->format(DATE_ATOM),
        );
        self::assertSame('2026-10-17T14:00:00+00:00', Temporal::datetime(1792245600, 'UTC')()->format(DATE_ATOM));
    }

    public function testAnExpressionIsCastWhenTheTreeIsEvaluated(): void
    {
        $port = (new Ports())->named('now');
        $now = Temporal::datetime($port, 'Europe/Warsaw');
        $port->setExpr(new StringLiteral('2026-10-17T08:00:00Z'));

        self::assertInstanceOf(DateTimeCast::class, $now);
        self::assertSame('10:00', $now()->format('H:i'));
    }

    public function testATimezoneGivenAsAnExpressionIsCastToo(): void
    {
        $value = Temporal::datetime('2026-10-17T08:00:00Z', new StringLiteral('Asia/Tokyo'));

        self::assertSame('17:00', $value()->format('H:i'));
    }

    public function testFactoriesChain(): void
    {
        $issued = Temporal::datetime('2026-01-31T09:00:00+01:00', 'Europe/Warsaw');
        $due = Temporal::add($issued, 'P1M');

        self::assertSame('2026-02-28', Temporal::format($due, 'Y-m-d')());
        self::assertSame('2026-03-03', Temporal::format(Temporal::add($issued, 'P1M', MonthOverflow::Overflow), 'Y-m-d')());
        self::assertSame('2026-01-30', Temporal::format(Temporal::sub($issued, 'P1D'), 'Y-m-d')());
        self::assertSame('2026-02-01', Temporal::format(Temporal::modify($issued, 'first day of next month'), 'Y-m-d')());
        self::assertSame('17:00', Temporal::format(Temporal::atTime($issued, '17:00'), 'H:i')());
        self::assertSame('2026-01-26', Temporal::format(Temporal::startOf($issued, CalendarUnit::Week), 'Y-m-d')());
        self::assertSame(6, Temporal::part($issued, DatePart::DayOfWeek)());
        self::assertSame(2419200, Temporal::secondsBetween($issued, $due)());
        self::assertSame(28, Temporal::diff($issued, $due)()->days);
        self::assertTrue(Temporal::timeOfDayBetween($issued, '09:00', '17:00')());
        self::assertSame('2026-02-03', Temporal::format(Temporal::addBusinessDays($issued, 2), 'Y-m-d')());
        self::assertSame(20, Temporal::businessDaysBetween($issued, $due)());
        self::assertFalse(Temporal::isBusinessDay($issued)());
        self::assertSame('09:00:00', Temporal::time('9:00')());
        self::assertSame($issued()->getTimestamp(), Temporal::timestamp($issued)());
    }

    public function testRanges(): void
    {
        $start = Temporal::datetime('2026-01-01T00:00:00+01:00', 'Europe/Warsaw');
        $end = Temporal::datetime('2026-02-01T00:00:00+01:00', 'Europe/Warsaw');
        $january = Temporal::range($start, $end);
        $fromFebruary = Temporal::range($end);

        self::assertTrue(Temporal::contains($january, $start)());
        self::assertFalse(Temporal::overlaps($january, $fromFebruary)());
        self::assertTrue(Temporal::anyContains($end, $january, $fromFebruary)());
        self::assertSame('2026-01-01', Temporal::format(Temporal::rangeStart(Temporal::intersection($january, Temporal::range($start))), 'Y-m-d')());
        self::assertSame('2026-02-01', Temporal::format(Temporal::rangeEnd($january), 'Y-m-d')());
    }

    public function testDecorationKeepsTheTemporalTypes(): void
    {
        $config = ExpressionTreeConfig::decoratedBy(static fn (Expression $node): Decorator => new ChannelDecorator($node, 'audit'));

        $start = Temporal::datetime('2026-01-01T00:00:00Z', 'UTC', $config);
        $interval = Temporal::interval('P1D', $config);
        $range = Temporal::range($start, Temporal::add($start, $interval, config: $config), $config);

        self::assertInstanceOf(DateTimeValue::class, $start);
        self::assertInstanceOf(IntervalValue::class, $interval);
        self::assertInstanceOf(RangeValue::class, $range);
        self::assertTrue(Temporal::contains($range, $start, $config)());
        self::assertContains('audit', ChannelDecorator::$channels);
    }
}
