<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Registry\Entry;

readonly class ExpressionEntry
{
    /**
     * @param ExpressionArgument[] $arguments
     */
    public function __construct(
        public string $key,
        public string $uid,
        public string $version,
        public string $fqcn,
        public string $type,
        public ExpressionAttributes $attributes,
        public array $arguments,
    ) {}
}
