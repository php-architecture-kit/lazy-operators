<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Application\Registry\Entry;

readonly class CallbackDetails
{
    /**
     * @param CallbackParameter[] $parameters
     */
    public function __construct(
        public string $name,
        public string $signature,
        public array $parameters,
        public ?string $returnType,
    ) {}
}
