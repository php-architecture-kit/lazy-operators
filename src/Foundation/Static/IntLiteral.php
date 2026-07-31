<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Static;

use PhpArchitecture\LazyOperators\Foundation\Type\IntegerValue;

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

    public static function formula(): string
    {
        return 'f(value) = value';
    }
}
