<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Functional\Temporal;

use PhpArchitecture\LazyOperators\Application\Builder\ExpressionBuilder;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception\EvaluationFailedException;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTree;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\StringLiteral;
use PhpArchitecture\LazyOperators\Infrastructure\Registry\ExpressionRegistry;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The scenario from issue #15: going shopping is impossible because the shop is open Monday to
 * Saturday, 09:00-17:00 Europe/Warsaw. Travel takes 30 minutes and shopping 45. The current time
 * comes through the ⟨now⟩ port, bound in UTC or with another offset; the answer must not depend on it.
 */
final class ShopOpeningHoursTest extends TestCase
{
    /**
     * @return iterable<string, array{string, bool}>
     */
    public static function moments(): iterable
    {
        yield 'Saturday 10:00 in Warsaw' => ['2026-10-17T08:00:00Z', true];
        yield 'Saturday 16:00, done at 17:15' => ['2026-10-17T14:00:00Z', false];
        yield 'Saturday 15:45, done exactly at 17:00' => ['2026-10-17T13:45:00Z', true];
        yield 'Saturday 08:15, arriving at 08:45' => ['2026-10-17T06:15:00Z', false];
        yield 'Sunday 11:00' => ['2026-10-18T09:00:00Z', false];
        yield 'Saturday 16:00 bound with an offset' => ['2026-10-17T16:00:00+02:00', false];
        yield 'Monday after the clock change, 09:00' => ['2026-10-26T07:30:00Z', true];
    }

    #[DataProvider('moments')]
    public function testItDecidesInTheShopsTimezone(string $now, bool $expected): void
    {
        $tree = self::build(self::canGoShopping());
        $tree->bind('now', new StringLiteral($now));

        self::assertSame($expected, $tree());
    }

    public function testAFailBranchSaysWhy(): void
    {
        $tree = self::build([
            'expression' => 'if_else',
            'arguments' => [
                'condition' => self::canGoShopping(),
                'then' => self::node('string_literal', ['value' => 'go']),
                'else' => self::node('fail', ['message' => self::node('string_literal', ['value' => 'the shop is open 09:00-17:00 Europe/Warsaw'])]),
            ],
        ]);

        $tree->bind('now', new StringLiteral('2026-10-17T08:00:00Z'));
        self::assertSame('go', $tree());

        $tree->bind('now', new StringLiteral('2026-10-18T09:00:00Z'));
        $this->expectException(EvaluationFailedException::class);
        $this->expectExceptionMessage('the shop is open 09:00-17:00 Europe/Warsaw');
        $tree();
    }

    /**
     * @return array<string, mixed>
     */
    private static function canGoShopping(): array
    {
        $now = self::node('cast_datetime', [
            'value' => self::node('runtime_port', ['name' => 'now']),
            'timezone' => self::node('string_literal', ['value' => 'Europe/Warsaw']),
        ]);
        $arrival = self::node('datetime_add', ['datetime' => $now, 'interval' => self::node('interval_literal', ['value' => 'PT30M'])]);
        $done = self::node('datetime_add', ['datetime' => $arrival, 'interval' => self::node('interval_literal', ['value' => 'PT45M'])]);

        return self::node('and', [
            'left' => self::node('and', [
                'left' => self::node('less_than_or_equal', [
                    'left' => self::node('datetime_part', ['datetime' => $arrival, 'part' => 'DayOfWeek']),
                    'right' => self::node('int_literal', ['value' => 6]),
                ]),
                'right' => self::node('time_of_day_between', [
                    'datetime' => $arrival,
                    'from' => self::node('time_literal', ['value' => '09:00']),
                    'to' => self::node('time_literal', ['value' => '17:00']),
                ]),
            ]),
            'right' => self::node('less_than_or_equal', [
                'left' => self::node('datetime_part', ['datetime' => $done, 'part' => 'SecondOfDay']),
                'right' => self::node('int_literal', ['value' => 17 * 3600]),
            ]),
        ]);
    }

    /**
     * @param array<string, mixed> $definition
     */
    private static function build(array $definition): ExpressionTree
    {
        return (new ExpressionBuilder(ExpressionRegistry::default()))->build($definition);
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
