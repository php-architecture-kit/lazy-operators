<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support;

use BcMath\Number;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\PrecisionNumberValue;

final class BcNumberLiteral implements PrecisionNumberValue
{
    public const KEY = 'bcmath_number_literal';
    public const UID = '688cd222-7fbb-4413-8bb2-a4d6fbadb684';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Number $value,
    ) {
    }

    public function __invoke(): int|float
    {
        return (string) $this->value + 0;
    }

    public function bcValue(): Number
    {
        return $this->value;
    }

    public static function formula(): string
    {
        return 'f(value) = value';
    }
}
