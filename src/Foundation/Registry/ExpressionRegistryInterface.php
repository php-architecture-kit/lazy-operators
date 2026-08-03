<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Registry;

use PhpArchitecture\LazyOperators\Foundation\Registry\Entry\ExpressionEntry;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;

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
