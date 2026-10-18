<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception;

use RuntimeException;

final class NonExistentLocalTimeException extends RuntimeException implements LazyOperatorsTemporalException
{
    public static function create(string $date, string $time, string $timezone): self
    {
        return new self(sprintf(
            '%s %s does not exist in %s: the clocks skip it when daylight saving time starts.',
            $date,
            $time,
            $timezone,
        ));
    }
}
