<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exception;

use RuntimeException;

final class MathFunctionUnavailableException extends RuntimeException implements LazyOperatorsMathException
{
    public static function create(string $function): self
    {
        return new self(sprintf(
            'The "%s()" function required by this Expression is not available in the current PHP build.',
            $function,
        ));
    }
}
