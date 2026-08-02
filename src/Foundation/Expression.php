<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation;

interface Expression
{
    public const KEY = 'key_not_defined';
    public const UID = '0000000-0000-0000-0000-000000000000';
    public const VERSION = '0.0';

    public function __invoke(): mixed;
}
