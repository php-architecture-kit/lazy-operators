<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Type;

use PhpArchitecture\LazyOperators\Foundation\Expression;

/**
 * @template T
 */
interface ArrayValue extends Expression
{
    /**
     * @return array<array-key,T>
     */
    public function __invoke(): array;
}
