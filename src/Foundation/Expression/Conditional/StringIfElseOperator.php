<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

/**
 * Same as IfElseOperator, built only when both branches are already StringValue. Stays a genuine
 * IfElseOperator subclass (not a wrapper) so instanceof checks against IfElseOperator keep working.
 */
final class StringIfElseOperator extends IfElseOperator implements StringValue
{
    public const KEY = 'string_if_else';
    public const UID = '82ce5847-bcc6-45f3-9768-c433dd661007';
    public const VERSION = '1.0';

    public function __invoke(): string
    {
        $value = parent::__invoke();
        assert(is_string($value));

        return $value;
    }
}
