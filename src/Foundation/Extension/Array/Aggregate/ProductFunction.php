<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Array\Aggregate;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Product')]
#[Formula('f(values) = the product of values')]
#[Description('Product returns the result of multiplying every value in the array together.')]
class ProductFunction implements NumberValue
{
    use GuardsNativeFunction;

    public const KEY = 'array_product';
    public const UID = '21fc7038-9611-4d8e-978b-ff94dc2cfe22';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'array_product';

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

        return array_product($values);
    }
}
