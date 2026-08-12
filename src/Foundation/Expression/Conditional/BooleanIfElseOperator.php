<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;

/**
 * Same as IfElseOperator, built only when both branches are already BooleanValue: lets
 * IfBuilder::build() hand back something Logical can consume directly, without an explicit Cast.
 * Stays a genuine IfElseOperator subclass (not a wrapper) so instanceof checks against
 * IfElseOperator keep working.
 */
final class BooleanIfElseOperator extends IfElseOperator implements BooleanValue
{
    public const KEY = 'boolean_if_else';
    public const UID = '990e07e8-13af-471e-8a5a-244f78908c37';
    public const VERSION = '1.0';

    public function __invoke(): bool
    {
        $value = parent::__invoke();
        assert(is_bool($value));

        return $value;
    }
}
