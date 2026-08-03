<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Formula
{
    public function __construct(
        public string $value,
    ) {}
}
