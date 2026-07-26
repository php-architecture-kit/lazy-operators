<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Support\GuardsNativeFunction;

class BaseConvertFunction implements Expression
{
    use GuardsNativeFunction;

    public const KEY = 'math_base_convert';
    public const UID = 'b6fd0652-03f5-4224-9d23-d7f1cead328f';
    public const VERSION = '1.0';
    private const NATIVE_FUNCTION = 'base_convert';

    public function __construct(
        public readonly Expression $value,
        public readonly Expression $fromBase,
        public readonly Expression $toBase,
    ) {
        self::guardAvailable(self::NATIVE_FUNCTION);
    }

    public function __invoke(): string
    {
        $value = ($this->value)();
        assert(is_string($value));

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
