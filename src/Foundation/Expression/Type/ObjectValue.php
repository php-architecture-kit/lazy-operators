<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Type;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;

/**
 * @template T of object
 */
interface ObjectValue extends Expression
{
    /**
     * @return T
     */
    public function __invoke(): object;
}
