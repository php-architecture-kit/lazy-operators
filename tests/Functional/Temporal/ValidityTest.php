<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Functional\Temporal;

use PhpArchitecture\LazyOperators\Application\Builder\ExpressionBuilder;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\CalendarUnit;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Temporal;
use PhpArchitecture\LazyOperators\Foundation\Expression\Ports;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\ArrayLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\StringLiteral;
use PhpArchitecture\LazyOperators\Infrastructure\Registry\ExpressionRegistry;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Validity: a period in which something holds, written as data or with the facade.
 */
final class ValidityTest extends TestCase
{
    /**
     * @return iterable<string, array{string, bool}>
     */
    public static function checks(): iterable
    {
        // Issued Wednesday 23 December 2026, 14:00 Warsaw; valid until the end of the 7th business
        // day after it, which is Thursday 7 January 2027 once the holidays are skipped.
        yield 'right after issuing' => ['2026-12-23T13:00:00Z', true];
        yield 'during the holidays' => ['2026-12-31T12:00:00Z', true];
        yield 'last minute of 7 January in Warsaw' => ['2027-01-07T22:59:59Z', true];
        yield 'midnight in Warsaw' => ['2027-01-07T23:00:00Z', false];
        yield 'before issuing' => ['2026-12-23T12:59:59Z', false];
    }

    #[DataProvider('checks')]
    public function testValidForSevenBusinessDaysFromIssuing(string $now, bool $expected): void
    {
        $issued = self::node('cast_datetime', [
            'value' => self::node('runtime_port', ['name' => 'issued']),
            'timezone' => self::node('string_literal', ['value' => 'Europe/Warsaw']),
        ]);
        $lastDay = self::node('business_days_add', [
            'datetime' => $issued,
            'days' => self::node('int_literal', ['value' => 7]),
            'holidays' => self::node('cast_array', ['value' => self::node('runtime_port', ['name' => 'holidays'])]),
        ]);

        $tree = (new ExpressionBuilder(ExpressionRegistry::default()))->build(self::node('range_contains', [
            'range' => self::node('datetime_range', [
                'start' => $issued,
                'end' => self::node('start_of', [
                    'datetime' => self::node('datetime_add', ['datetime' => $lastDay, 'interval' => self::node('interval_literal', ['value' => 'P1D'])]),
                    'unit' => 'Day',
                ]),
            ]),
            'datetime' => self::node('cast_datetime', [
                'value' => self::node('runtime_port', ['name' => 'now']),
                'timezone' => self::node('string_literal', ['value' => 'Europe/Warsaw']),
            ]),
        ]));

        $tree->bind('issued', new StringLiteral('2026-12-23T14:00:00+01:00'));
        $tree->bind('holidays', new ArrayLiteral(['2026-12-24', '2026-12-25', '2026-12-26', '2027-01-01', '2027-01-06']));
        $tree->bind('now', new StringLiteral($now));

        self::assertSame($expected, $tree());
    }

    public function testValidityWithGapsAndAnOpenEnd(): void
    {
        // Midnight in Warsaw is +01:00 in winter and +02:00 in summer, so the boundaries are the
        // start of the day in the zone rather than literals with a fixed offset.
        $at = static fn (string $date) => Temporal::startOf(Temporal::datetime($date . 'T12:00:00Z', 'Europe/Warsaw'), CalendarUnit::Day);
        $port = (new Ports())->named('now');

        $valid = Temporal::anyContains(
            Temporal::datetime($port, 'Europe/Warsaw'),
            Temporal::range($at('2026-01-01'), $at('2026-02-01')),
            Temporal::range($at('2026-03-01'), $at('2026-04-01')),
            Temporal::range($at('2027-01-01')),
        );

        $results = [];

        foreach (['2026-01-15T12:00:00Z', '2026-02-15T12:00:00Z', '2026-03-31T21:59:59Z', '2026-03-31T22:00:00Z', '2031-06-01T00:00:00Z'] as $now) {
            $port->setExpr(new StringLiteral($now));
            $results[$now] = $valid();
        }

        self::assertSame([
            '2026-01-15T12:00:00Z' => true,
            '2026-02-15T12:00:00Z' => false,
            '2026-03-31T21:59:59Z' => true,
            '2026-03-31T22:00:00Z' => false,
            '2031-06-01T00:00:00Z' => true,
        ], $results);
    }

    /**
     * @param array<string, mixed> $arguments
     *
     * @return array<string, mixed>
     */
    private static function node(string $expression, array $arguments): array
    {
        return ['expression' => $expression, 'arguments' => $arguments];
    }
}
