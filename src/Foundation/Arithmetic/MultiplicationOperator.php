<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;

class MultiplicationOperator implements NumberValue
{
    public const KEY = 'multiplication';
    public const UID = '0ed96a9f-75dc-45bc-a9f5-5dbdb48cbd5a';
    public const VERSION = '1.0';

    public function __construct(
        public readonly NumberValue $left,
        public readonly NumberValue $right,
    ) {}

    public function __invoke(): float|int
    {
        return ($this->left)() * ($this->right)();
    }

    public static function formula(): string
    {
        return 'f(left, right) = left x right';
    }
}
