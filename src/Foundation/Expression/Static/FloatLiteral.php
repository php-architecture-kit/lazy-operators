<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Static;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\FloatValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Static')]
#[Name('Float Literal')]
#[Formula('f(value) = value')]
#[Description('Float Literal wraps a raw floating point number as an Expression, returning it unchanged when invoked.')]
class FloatLiteral implements FloatValue
{
    public const KEY = 'float_literal';
    public const UID = '582fcda4-aac0-4815-b64e-b59c8759f009';
    public const VERSION = '1.0';

    public function __construct(
        public readonly float $value,
    ) {
    }

    public function __invoke(): float
    {
        return $this->value;
    }
}
