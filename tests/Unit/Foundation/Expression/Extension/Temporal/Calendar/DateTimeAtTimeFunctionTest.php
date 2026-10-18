<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Calendar;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Calendar\DateTimeAtTimeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DstFold;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DstGap;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\NonExistentLocalTimeException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\TimeLiteral;
use PHPUnit\Framework\TestCase;

final class DateTimeAtTimeFunctionTest extends TestCase
{
    public function testItMovesToTheTimeOnTheSameDay(): void
    {
        $at = new DateTimeAtTimeFunction(new DateTimeLiteral('2026-10-17T08:15:00Z', 'Europe/Warsaw'), new TimeLiteral('17:00'));

        self::assertSame('2026-10-17T17:00:00+02:00', $at()->format(DATE_ATOM));
    }

    public function testASkippedTimeMovesForwardByDefault(): void
    {
        $at = new DateTimeAtTimeFunction(new DateTimeLiteral('2026-03-29T12:00:00+02:00', 'Europe/Warsaw'), new TimeLiteral('02:30'));

        self::assertSame('2026-03-29T03:30:00+02:00', $at()->format(DATE_ATOM));
    }

    public function testASkippedTimeCanBeRejected(): void
    {
        $at = new DateTimeAtTimeFunction(
            new DateTimeLiteral('2026-03-29T12:00:00+02:00', 'Europe/Warsaw'),
            new TimeLiteral('02:30'),
            DstGap::Reject,
        );

        $this->expectException(NonExistentLocalTimeException::class);
        $this->expectExceptionMessage('2026-03-29 02:30:00 does not exist in Europe/Warsaw');

        $at();
    }

    public function testARepeatedTimeResolvesToTheSecondOccurrenceByDefault(): void
    {
        $at = new DateTimeAtTimeFunction(new DateTimeLiteral('2026-10-25T12:00:00+01:00', 'Europe/Warsaw'), new TimeLiteral('02:30'));

        self::assertSame('2026-10-25T02:30:00+01:00', $at()->format(DATE_ATOM));
    }

    public function testARepeatedTimeCanResolveToTheFirstOccurrence(): void
    {
        $at = new DateTimeAtTimeFunction(
            new DateTimeLiteral('2026-10-25T12:00:00+01:00', 'Europe/Warsaw'),
            new TimeLiteral('02:30'),
            fold: DstFold::Earlier,
        );

        self::assertSame('2026-10-25T02:30:00+02:00', $at()->format(DATE_ATOM));
    }

    public function testFoldLaterMovesOffTheFirstOccurrence(): void
    {
        $at = new DateTimeAtTimeFunction(
            new DateTimeLiteral('2026-10-25T02:30:00+02:00', 'Europe/Warsaw'),
            new TimeLiteral('02:30'),
            fold: DstFold::Later,
        );

        self::assertSame('2026-10-25T02:30:00+01:00', $at()->format(DATE_ATOM));
    }

    public function testAThirtyMinuteClockChangeIsHandledToo(): void
    {
        // Australia/Lord_Howe moves its clocks back by 30 minutes, from 02:00 to 01:30, on 5 April 2026.
        $at = new DateTimeAtTimeFunction(
            new DateTimeLiteral('2026-04-05T12:00:00+10:30', 'Australia/Lord_Howe'),
            new TimeLiteral('01:45'),
            fold: DstFold::Earlier,
        );

        self::assertSame('2026-04-05T01:45:00+11:00', $at()->format(DATE_ATOM));
    }
}
