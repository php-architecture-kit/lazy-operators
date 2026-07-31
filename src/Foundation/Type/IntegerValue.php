<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Type;

interface IntegerValue extends NumberValue
{
    public function __invoke(): int;
}
