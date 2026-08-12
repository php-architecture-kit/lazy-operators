<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;

/**
 * Same as SwitchCaseOperator, built only when every case value and the default (when present) are
 * already BooleanValue. Stays a genuine SwitchCaseOperator subclass (not a wrapper) so instanceof
 * checks against SwitchCaseOperator keep working.
 */
final class BooleanSwitchCaseOperator extends SwitchCaseOperator implements BooleanValue
{
    public const KEY = 'boolean_switch_case';
    public const UID = 'be8bc7e8-c0d0-46ec-8354-39d9003d256b';
    public const VERSION = '1.0';

    public function __invoke(): bool
    {
        $value = parent::__invoke();
        assert(is_bool($value));

        return $value;
    }
}
