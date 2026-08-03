<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Type;

interface IntegerValue extends NumberValue
{
    public function __invoke(): int;
}
