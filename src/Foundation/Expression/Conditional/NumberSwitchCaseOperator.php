<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;

/**
 * Same as SwitchCaseOperator, built only when every case value and the default (when present) are
 * already NumberValue: lets SwitchBuilder::build() hand back something Arithmetic/Logical can
 * consume directly, without an explicit Cast. Stays a genuine SwitchCaseOperator subclass (not a
 * wrapper) so instanceof checks against SwitchCaseOperator keep working.
 */
final class NumberSwitchCaseOperator extends SwitchCaseOperator implements NumberValue
{
    public const KEY = 'number_switch_case';
    public const UID = 'da1bbfc2-ce01-4a84-af65-2c6187e5e937';
    public const VERSION = '1.0';

    public function __invoke(): int|float
    {
        $value = parent::__invoke();
        assert(is_int($value) || is_float($value));

        return $value;
    }
}
