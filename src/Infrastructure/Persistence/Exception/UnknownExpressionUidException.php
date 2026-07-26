<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception;

use RuntimeException;

final class UnknownExpressionUidException extends RuntimeException implements LazyOperatorsPersistenceException
{
    public static function create(string $uid): self
    {
        return new self(sprintf('No serializer registered for Expression uid "%s".', $uid));
    }
}
