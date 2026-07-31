<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Static;

use PhpArchitecture\LazyOperators\Foundation\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Integer Literal')]
#[Formula('f(value) = value')]
#[Description('Integer Literal wraps a raw integer as an Expression, returning it unchanged when invoked.')]
class IntLiteral implements IntegerValue
{
    public const KEY = 'int_literal';
    public const UID = '1d22eff7-58cc-4ee6-99e9-67d3385e4fc4';
    public const VERSION = '1.0';

    public function __construct(
        public readonly int $value,
    ) {
    }

    public function __invoke(): int
    {
        return $this->value;
    }
}
