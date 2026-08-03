<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Allocation;

use LogicException;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\ArrayValue;
use PhpArchitecture\LazyOperators\Foundation\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

/**
 * @implements ArrayValue<float>
 */
#[Group('Allocation')]
#[Name('Allocation')]
#[Formula('f(amount, shares, precision) = amount split proportionally to shares, '
            . 'each rounded to precision, with the rounding remainder folded into one share; '
            . 'computed via bcmath when available, native float arithmetic otherwise')]
#[Description('Allocation splits an amount proportionally across a list of shares, rounds each part to the given precision, and folds the rounding remainder into one share.')]
class AllocationFunction implements ArrayValue
{
    public const KEY = 'allocation';
    public const UID = 'ea7ab05f-a239-43c9-b61b-d83b8a3a62a1';
    public const VERSION = '1.0';

    /**
     * Escape hatch for callers who want the native float strategy even when ext-bcmath is loaded;
     * not a constructor argument since it's a runtime toggle, not part of this node's identity.
     */
    public bool $useBcMathIfAvailable = true;

    /**
     * @var list<NumberValue>
     */
    public readonly array $shares;

    public function __construct(
        public readonly NumberValue $amount,
        public readonly IntegerValue $precision,
        public readonly AllocationRemainderTarget $remainderTarget,
        NumberValue $firstShare,
        NumberValue ...$restShares,
    ) {
        $this->shares = array_values([$firstShare, ...$restShares]);
    }

    /**
     * @return list<float>
     */
    public function __invoke(): array
    {
        $amount = ($this->amount)();

        $precision = ($this->precision)();

        $shareValues = array_values(array_map(static function (NumberValue $share): int|float {
            $value = $share();

            return $value;
        }, $this->shares));

        // ext-bcmath is only ever "suggested" (see composer.json), not required: when it's loaded, the
        // split itself is computed in exact decimal arithmetic instead of float, avoiding the rounding
        // drift native float division/multiplication can accumulate across the shares; when it's not
        // loaded (or a caller opts out via useBcMathIfAvailable), this falls back to the plain float
        // arithmetic PHP always has.
        return $this->useBcMathIfAvailable && function_exists('bcadd')
            ? self::allocateWithBcMath($amount, $shareValues, $precision, $this->remainderTarget)
            : self::allocateWithNativeFloats($amount, $shareValues, $precision, $this->remainderTarget);
    }

    public function useBcMath(bool $use): self
    {
        if ($use && !function_exists('bcadd')) {
            throw new LogicException("BC Math extension is not available.");
        }

        $this->useBcMathIfAvailable = $use;

        return $this;
    }

    /**
     * @param list<int|float> $shareValues
     *
     * @return list<float>
     */
    private static function allocateWithNativeFloats(int|float $amount, array $shareValues, int $precision, AllocationRemainderTarget $remainderTarget): array
    {
        $totalShares = array_sum($shareValues);

        $allocated = array_values(array_map(
            static fn (int|float $share): float => round($amount * $share / $totalShares, $precision),
            $shareValues,
        ));

        $remainder = round($amount - array_sum($allocated), $precision);

        if ($remainder !== 0.0) {
            $index = self::remainderIndex($remainderTarget, $allocated);
            $allocated[$index] = round($allocated[$index] + $remainder, $precision);
        }

        return array_values($allocated);
    }

    /**
     * @param list<int|float> $shareValues
     *
     * @return list<float>
     */
    private static function allocateWithBcMath(int|float $amount, array $shareValues, int $precision, AllocationRemainderTarget $remainderTarget): array
    {
        $scale = $precision + 10; // guard digits so the intermediate bcdiv/bcmul truncation doesn't bite before the final bcround

        $amountString = (string) $amount;

        $totalSharesString = array_reduce(
            $shareValues,
            static fn (string $carry, int|float $share): string => bcadd($carry, (string) $share, $scale),
            '0',
        );

        $allocated = array_map(
            static fn (int|float $share): string => bcround(
                bcdiv(bcmul($amountString, (string) $share, $scale), $totalSharesString, $scale),
                $precision,
            ),
            $shareValues,
        );

        $roundedAmountString = bcround($amountString, $precision);
        $sumString = array_reduce($allocated, static fn (string $carry, string $value): string => bcadd($carry, $value, $scale), '0');
        $remainderString = bcsub($roundedAmountString, $sumString, $precision);

        if (bccomp($remainderString, '0', $precision) !== 0) {
            $index = self::remainderIndex($remainderTarget, array_map(static fn (string $value): float => (float) $value, $allocated));
            $allocated[$index] = bcadd($allocated[$index], $remainderString, $precision);
        }

        return array_values(array_map(static fn (string $value): float => (float) $value, $allocated));
    }

    /**
     * @param list<float> $allocated
     */
    private static function remainderIndex(AllocationRemainderTarget $remainderTarget, array $allocated): int
    {
        return match ($remainderTarget) {
            AllocationRemainderTarget::First => 0,
            AllocationRemainderTarget::Last => count($allocated) - 1,
            AllocationRemainderTarget::Largest => self::indexOfExtreme($allocated, largest: true),
            AllocationRemainderTarget::Smallest => self::indexOfExtreme($allocated, largest: false),
        };
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
}
