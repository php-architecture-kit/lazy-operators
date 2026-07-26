<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation;

interface Expression
{
    public function __invoke(): mixed;
}
