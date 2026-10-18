<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Reading;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\InvalidDateTimeException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading\TimeOfDayBetweenFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\StringLiteral;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TimeOfDayBetweenFunctionTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string, string, bool}>
     */
    public static function windows(): iterable
    {
        yield 'inside' => ['12:00', '09:00', '17:00', true];
        yield 'at the start' => ['09:00', '09:00', '17:00', true];
        yield 'at the end' => ['17:00', '09:00', '17:00', false];
        yield 'before' => ['08:59', '09:00', '17:00', false];
        yield 'overnight, late' => ['23:00', '22:00', '06:00', true];
        yield 'overnight, early' => ['05:59', '22:00', '06:00', true];
        yield 'overnight, daytime' => ['12:00', '22:00', '06:00', false];
        yield 'empty window' => ['09:00', '09:00', '09:00', false];
    }

    #[DataProvider('windows')]
    public function testItChecksTheWallClockAgainstAHalfOpenWindow(string $time, string $from, string $to, bool $expected): void
    {
        $between = new TimeOfDayBetweenFunction(
            new DateTimeLiteral("2026-10-17T{$time}:00+02:00", 'Europe/Warsaw'),
            new StringLiteral($from),
            new StringLiteral($to),
        );

        self::assertSame($expected, $between());
    }

    public function testTheWallClockIsReadInTheDateTimesTimezone(): void
    {
        $between = new TimeOfDayBetweenFunction(
            new DateTimeLiteral('2026-10-17T15:30:00Z', 'Europe/Warsaw'),
            new StringLiteral('09:00'),
            new StringLiteral('17:00'),
        );

        self::assertFalse($between());
    }

    public function testAnInvalidBoundIsRejected(): void
    {
        $this->expectException(InvalidDateTimeException::class);

        (new TimeOfDayBetweenFunction(
            new DateTimeLiteral('2026-10-17T12:00:00Z', 'UTC'),
            new StringLiteral('9am'),
            new StringLiteral('17:00'),
        ))();
    }
}
