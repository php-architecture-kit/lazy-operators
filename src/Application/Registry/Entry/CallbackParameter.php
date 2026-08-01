<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Application\Registry\Entry;

readonly class CallbackParameter
{
    public function __construct(
        public string $name,
        public string $type,
    ) {}
}
