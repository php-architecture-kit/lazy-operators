<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

/**
 * Same as SwitchCaseOperator, built only when every case value and the default (when present) are
 * already StringValue. Stays a genuine SwitchCaseOperator subclass (not a wrapper) so instanceof
 * checks against SwitchCaseOperator keep working.
 */
final class StringSwitchCaseOperator extends SwitchCaseOperator implements StringValue
{
    public const KEY = 'string_switch_case';
    public const UID = '19043c50-52f8-4c91-9cc6-cf113c5511af';
    public const VERSION = '1.0';

    public function __invoke(): string
    {
        $value = parent::__invoke();
        assert(is_string($value));

        return $value;
    }
}
