<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Support\GuardsNativeFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Math | Conversion')]
#[Name('Base Convert')]
#[Formula('f(value, fromBase, toBase) = value converted from fromBase to toBase')]
#[Description('Base Convert converts a number, given as a string, from one base to another, each between 2 and 36.')]
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
}
