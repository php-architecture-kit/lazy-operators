<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class MaxFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_max';
    public const UID = '342a594d-1c6b-45bb-90a1-cb9a5f6044bb';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'max';

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

        return count($values) === 1 ? $values[0] : max(...$values);
    }

    public static function formula(): string
    {
        return 'f(values) = the greatest of values';
    }
}
