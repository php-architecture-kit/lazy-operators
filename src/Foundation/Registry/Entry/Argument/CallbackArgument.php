<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Registry\Entry\Argument;

use PhpArchitecture\LazyOperators\Foundation\Registry\Entry\ExpressionArgument;

/**
 * Marks an argument whose value is a Closure (see CallbackOperator). A UI should offer a picker
 * over named, pre-registered callbacks rather than a raw code input.
 */
readonly class CallbackArgument extends ExpressionArgument
{
}
