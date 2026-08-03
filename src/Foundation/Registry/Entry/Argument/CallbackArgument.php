<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Application\Registry\Entry\Argument;

use PhpArchitecture\LazyOperators\Application\Registry\Entry\ExpressionArgument;

/**
 * Marks an argument whose value is a Closure that must be registered under a name via
 * PhpArchitecture\LazyOperators\Infrastructure\Persistence\CallbackRegistry::register()
 * before it can be resolved or persisted (see CallbackOperator). A UI should offer a
 * picker over that registry's registered names rather than a raw code input.
 */
readonly class CallbackArgument extends ExpressionArgument
{
}
