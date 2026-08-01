<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Meta\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class TypeOf
{
    public function __construct(
        public string $value,
    ) {}
}
