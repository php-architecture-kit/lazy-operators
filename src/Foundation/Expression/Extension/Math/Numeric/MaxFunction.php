<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Group('Math | Numeric')]
#[Name('Max')]
#[Formula('f(values) = the greatest of values')]
#[Description('Max returns the greatest of the given values.')]
class MaxFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_max';
    public const UID = '342a594d-1c6b-45bb-90a1-cb9a5f6044bb';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'max';

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

        return count($values) === 1 ? $values[0] : max(...$values);
    }
}
