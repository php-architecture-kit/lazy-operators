<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\List\Aggregate;

use LogicException;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Group('List')]
#[Name('Sum')]
#[Formula('f(values) = the sum of values; computed via bcmath when available, native arithmetic otherwise')]
#[Description('Sum returns the total of every value in the array added together.')]
class SumFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'array_sum';
    public const UID = 'df303599-2e52-460d-825c-d5c2aa02ea0f';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'array_sum';

    /**
     * Guard digits well past a PHP float's ~17 significant digits, so bcadd only ever
     * avoids accumulated intermediate float error across many terms — the final cast
     * back to float is still the unavoidable boundary, same as the native path.
     */
    private const BC_SCALE = 20;

    /**
     * Escape hatch for callers who want the native float strategy even when ext-bcmath is loaded;
     * not a constructor argument since it's a runtime toggle, not part of this node's identity.
     */
    public bool $useBcMathIfAvailable = true;

    /**
     * @var NumberValue[]
     */
    public readonly array $values;

    public function __construct(
        NumberValue $first,
        NumberValue ...$rest,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);

        $this->values = [$first, ...$rest];
    }

    public function __invoke(): int|float
    {
        $values = array_map(static function (NumberValue $expression): int|float {
            $value = $expression();

            return $value;
        }, $this->values);

        if (self::allInts($values) || !($this->useBcMathIfAvailable && function_exists('bcadd'))) {
            return array_sum($values);
        }

        $sum = array_reduce(
            $values,
            static fn (string $carry, int|float $value): string => bcadd($carry, (string) $value, self::BC_SCALE),
            '0',
        );

        return (float) $sum;
    }

    public function useBcMath(bool $use): self
    {
        if ($use && !function_exists('bcadd')) {
            throw new LogicException('BC Math extension is not available.');
        }

        $this->useBcMathIfAvailable = $use;

        return $this;
    }

    /**
     * @param array<int|float> $values
     */
    private static function allInts(array $values): bool
    {
        foreach ($values as $value) {
            if (!is_int($value)) {
                return false;
            }
        }

        return true;
    }
}
