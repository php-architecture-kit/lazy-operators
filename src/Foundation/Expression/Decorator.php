<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation;

interface Decorator extends Expression
{
    public function unwrap(): Expression;
}
