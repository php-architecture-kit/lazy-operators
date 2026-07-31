<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Type\StringValue;

class BaseConvertFunction implements StringValue
{
    use GuardsNativeFunction;

    public const KEY = 'math_base_convert';
    public const UID = 'b6fd0652-03f5-4224-9d23-d7f1cead328f';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'base_convert';

    public function __construct(
        public readonly StringValue $value,
        public readonly NumberValue $fromBase,
        public readonly NumberValue $toBase,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): string
    {
        $value = ($this->value)();

        $fromBase = ($this->fromBase)();
        assert(is_int($fromBase));

        $toBase = ($this->toBase)();
        assert(is_int($toBase));

        return base_convert($value, $fromBase, $toBase);
    }

    public static function formula(): string
    {
        return 'f(value, fromBase, toBase) = value converted from fromBase to toBase';
    }
}
