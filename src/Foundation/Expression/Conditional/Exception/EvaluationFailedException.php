<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception;

use RuntimeException;

final class EvaluationFailedException extends RuntimeException implements LazyOperatorsConditionalException
{
    public static function create(string $message): self
    {
        return new self($message);
    }
}
