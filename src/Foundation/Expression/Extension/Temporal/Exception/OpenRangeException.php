<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception;

use RuntimeException;

final class OpenRangeException extends RuntimeException implements LazyOperatorsTemporalException
{
    public static function noEnd(string $range): self
    {
        return new self(sprintf('The range %s has no end.', $range));
    }
}
