<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Math | Numeric')]
#[Name('Min')]
#[Formula('f(values) = the smallest of values')]
#[Description('Min returns the smallest of the given values.')]
class MinFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_min';
    public const UID = 'a927803a-34a6-490e-87a3-f750b0440ade';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'min';

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

        return count($values) === 1 ? $values[0] : min(...$values);
    }
}
