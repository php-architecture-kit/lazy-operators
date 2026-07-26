<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception;

use RuntimeException;

class IncompatibleExpressionVersionException extends RuntimeException
{
    public function __construct(string $uid, string $storedVersion, string $currentVersion)
    {
        parent::__construct(sprintf(
            'Stored version "%s" for Expression uid "%s" is incompatible with the currently registered version "%s".',
            $storedVersion,
            $uid,
            $currentVersion,
        ));
    }
}
