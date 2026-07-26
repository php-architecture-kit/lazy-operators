<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class BinDecFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_bindec';
    public const UID = '0811b4cf-93dc-41da-b945-5b0ae7a14521';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'bindec';

    public function __construct(
        public readonly Expression $value,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): int|float
    {
        $value = ($this->value)();
        assert(is_string($value));

        return bindec($value);
    }

    public static function formula(): string
    {
        return 'f(value) = decimal value of the binary string value';
    }
}
