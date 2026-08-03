<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Allocation\Exception;

use InvalidArgumentException;

final class EmptySharesException extends InvalidArgumentException implements LazyOperatorsAllocationException
{
    public static function create(): self
    {
        return new self('At least one share is required to allocate an amount.');
    }
}
