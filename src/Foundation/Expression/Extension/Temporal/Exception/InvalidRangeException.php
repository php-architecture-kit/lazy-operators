<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception;

use InvalidArgumentException;

final class InvalidRangeException extends InvalidArgumentException implements LazyOperatorsTemporalException
{
    public static function endNotAfterStart(string $start, string $end): self
    {
        return new self(sprintf('A range must end after it starts; got [%s, %s).', $start, $end));
    }
}
