<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Static;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Static')]
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
