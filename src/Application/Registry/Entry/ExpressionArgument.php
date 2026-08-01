<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Application\Registry\Entry;

use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;

readonly class ExpressionArgument
{
    public function __construct(
        public string $name,
        public string $type,
        public ?string $itemType,
        public bool $spread,
        public bool $optional,
        public ?string $defaultValue,
        public ?Description $description,
    ) {}
}
