<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception;

use RuntimeException;

class UnknownExpressionUidException extends RuntimeException
{
    public function __construct(string $uid)
    {
        parent::__construct(sprintf('No serializer registered for Expression uid "%s".', $uid));
    }
}
