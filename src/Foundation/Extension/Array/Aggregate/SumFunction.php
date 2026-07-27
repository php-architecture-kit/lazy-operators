<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Array\Aggregate;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class SumFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'array_sum';
    public const UID = 'df303599-2e52-460d-825c-d5c2aa02ea0f';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'array_sum';

    /**
     * @var Expression[]
     */
    public readonly array $values;

    public function __construct(
        Expression $first,
        Expression ...$rest,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);

        $this->values = [$first, ...$rest];
    }

    public function __invoke(): int|float
    {
        $values = array_map(static function (Expression $expression): int|float {
            $value = $expression();
            assert(is_int($value) || is_float($value));

            return $value;
        }, $this->values);

        return array_sum($values);
    }

    public static function formula(): string
    {
        return 'f(values) = the sum of values';
    }
}
