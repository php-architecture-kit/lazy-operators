<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception;

use RuntimeException;

final class UnsupportedExpressionException extends RuntimeException implements LazyOperatorsPersistenceException
{
    public static function create(string $class): self
    {
        return new self(sprintf('No serializer registered for Expression class "%s".', $class));
    }
}
