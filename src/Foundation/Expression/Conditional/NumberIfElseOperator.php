<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;

/**
 * Same as IfElseOperator, built only when both branches are already NumberValue: lets
 * IfBuilder::build() hand back something Arithmetic/Logical can consume directly, without an
 * explicit Cast. Stays a genuine IfElseOperator subclass (not a wrapper) so instanceof checks
 * against IfElseOperator keep working.
 */
final class NumberIfElseOperator extends IfElseOperator implements NumberValue
{
    public const KEY = 'number_if_else';
    public const UID = '931a6e6e-9b53-4579-8da0-180d1ac0220f';
    public const VERSION = '1.0';

    public function __invoke(): int|float
    {
        $value = parent::__invoke();
        assert(is_int($value) || is_float($value));

        return $value;
    }
}
