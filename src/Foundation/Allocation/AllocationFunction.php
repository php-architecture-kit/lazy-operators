<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Allocation;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class AllocationFunction implements Expression
{
    public const KEY = 'allocation';
    public const UID = 'ea7ab05f-a239-43c9-b61b-d83b8a3a62a1';
    public const VERSION = '1.0';

    /**
     * @var list<Expression>
     */
    public readonly array $shares;

    public function __construct(
        public readonly Expression $amount,
        public readonly Expression $precision,
        public readonly AllocationRemainderTarget $remainderTarget,
        Expression $firstShare,
        Expression ...$restShares,
    ) {
        $this->shares = array_values([$firstShare, ...$restShares]);
    }

    /**
     * @return list<float>
     */
    public function __invoke(): array
    {
        $amount = ($this->amount)();
        assert(is_int($amount) || is_float($amount));

        $precision = ($this->precision)();
        assert(is_int($precision));

        $shareValues = array_values(array_map(static function (Expression $share): int|float {
            $value = $share();
            assert(is_int($value) || is_float($value));

            return $value;
        }, $this->shares));

        $totalShares = array_sum($shareValues);

        $allocated = array_values(array_map(
            static fn (int|float $share): float => round($amount * $share / $totalShares, $precision),
            $shareValues,
        ));

        $remainder = round($amount - array_sum($allocated), $precision);

        if ($remainder !== 0.0) {
            $index = match ($this->remainderTarget) {
                AllocationRemainderTarget::First => 0,
                AllocationRemainderTarget::Last => count($allocated) - 1,
                AllocationRemainderTarget::Largest => self::indexOfExtreme($allocated, largest: true),
                AllocationRemainderTarget::Smallest => self::indexOfExtreme($allocated, largest: false),
            };

            $allocated[$index] = round($allocated[$index] + $remainder, $precision);
        }

        return array_values($allocated);
    }

    /**
     * @param list<float> $values
     */
    private static function indexOfExtreme(array $values, bool $largest): int
    {
        $extremeIndex = 0;

        foreach ($values as $index => $value) {
            if ($largest ? $value > $values[$extremeIndex] : $value < $values[$extremeIndex]) {
                $extremeIndex = $index;
            }
        }

        return $extremeIndex;
    }

    public static function formula(): string
    {
        return 'f(amount, shares, precision) = amount split proportionally to shares, '
            . 'each rounded to precision, with the rounding remainder folded into one share';
    }
}
