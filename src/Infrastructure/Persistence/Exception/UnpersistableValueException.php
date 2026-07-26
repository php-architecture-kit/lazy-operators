<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception;

use RuntimeException;

class UnpersistableValueException extends RuntimeException
{
    public function __construct(mixed $value)
    {
        parent::__construct(sprintf(
            'Value wraps a "%s", which is not JSON-safe and cannot be persisted.',
            get_debug_type($value),
        ));
    }
}
