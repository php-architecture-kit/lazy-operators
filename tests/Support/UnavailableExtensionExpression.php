<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\RequiresExtension;

#[RequiresExtension('definitely_not_a_real_extension')]
final class UnavailableExtensionExpression implements Expression
{
    public function __invoke(): mixed
    {
        return null;
    }
}
