<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support;

use BcMath\Number;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\PrecisionNumberValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;

/**
 * Bridges any *other* NumberValue expression (e.g. a Math result, a plain IntLiteral) into
 * PrecisionNumberValue, so it can flow into a BcMath operator. The conversion to Number only
 * happens inside bcValue(), when a BcMath node actually needs it — never eagerly at construction
 * time. Not to be confused with BcNumberLiteral: that class is the Literal-boundary node for a raw
 * BcMath\Number you already have; this class is the bridge for when you don't (you have some other
 * NumberValue expression instead).
 */
#[Group('BcMath')]
#[Name('Number Value To Precision Adapter')]
#[Formula('f(value) = value, bridged into BcMath\Number on demand')]
#[Description('Number Value To Precision Adapter bridges another Expression\'s numeric result into a BcMath\\Number on demand, so BcMath operators can consume it without an eager conversion.')]
final class NumberValueToPrecisionAdapter implements PrecisionNumberValue
{
    public const KEY = 'bcmath_precision_number_adapter';
    public const UID = 'ec7bcbed-1371-4c61-9967-59ae648319fd';
    public const VERSION = '1.0';

    public function __construct(
        public readonly NumberValue $value,
    ) {
    }

    public function __invoke(): int|float
    {
        return ($this->value)();
    }

    public function bcValue(): Number
    {
        return new Number((string) ($this->value)());
    }
}
