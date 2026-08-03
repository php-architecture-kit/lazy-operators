<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Meta\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Group
{
    public function __construct(
        public string $value,
    ) {}
}
