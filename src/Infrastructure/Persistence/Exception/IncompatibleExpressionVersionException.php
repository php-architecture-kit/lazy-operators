<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception;

use RuntimeException;

final class IncompatibleExpressionVersionException extends RuntimeException implements LazyOperatorsPersistenceException
{
    public static function create(string $uid, string $storedVersion, string $currentVersion): self
    {
        return new self(sprintf(
            'Stored version "%s" for Expression uid "%s" is incompatible with the currently registered version "%s".',
            $storedVersion,
            $uid,
            $currentVersion,
        ));
    }
}
