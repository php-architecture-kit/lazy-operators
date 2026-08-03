<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Application\Registry;

use PhpArchitecture\LazyOperators\Application\Registry\Entry\ExpressionEntry;
use PhpArchitecture\LazyOperators\Foundation\Expression;

interface ExpressionRegistryInterface
{
    /**
     * @return ExpressionEntry[]
     */
    public function getAll(): array;

    /**
     * @param class-string<Expression> $className
     */
    public function register(string $className): void;
}
