<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Type;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;

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
