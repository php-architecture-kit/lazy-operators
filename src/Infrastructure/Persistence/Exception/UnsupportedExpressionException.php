<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception;

use RuntimeException;

class UnsupportedExpressionException extends RuntimeException
{
    public function __construct(string $class)
    {
        parent::__construct(sprintf('No serializer registered for Expression class "%s".', $class));
    }
}
