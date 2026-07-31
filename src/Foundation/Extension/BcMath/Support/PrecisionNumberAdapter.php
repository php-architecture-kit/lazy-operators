<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support;

use BcMath\Number;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\PrecisionNumberValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

/**
 * Lazy bridge from any other Expression in the library (e.g. a Math result, a plain IntLiteral) into
 * PrecisionNumberValue. The conversion to Number only happens inside bcValue(), when a BcMath node
 * actually needs it — never eagerly at construction time.
 */
#[Name('Precision Number Adapter')]
#[Formula('f(value) = value, bridged into BcMath\Number on demand')]
#[Description('Precision Number Adapter bridges another Expression\'s numeric result into a BcMath\\Number on demand, so BcMath operators can consume it without an eager conversion.')]
final class PrecisionNumberAdapter implements PrecisionNumberValue
{
    public const KEY = 'bcmath_precision_number_adapter';
    public const UID = 'ec7bcbed-1371-4c61-9967-59ae648319fd';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $value,
    ) {
    }

    public function __invoke(): int|float
    {
        $value = ($this->value)();
        assert(is_int($value) || is_float($value));

        return $value;
    }

    public function bcValue(): Number
    {
        $value = ($this->value)();
        assert(is_numeric($value));

        return new Number((string) $value);
    }
}
