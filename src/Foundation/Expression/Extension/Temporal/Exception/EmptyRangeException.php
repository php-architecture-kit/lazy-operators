<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception;

use RuntimeException;

final class EmptyRangeException extends RuntimeException implements LazyOperatorsTemporalException
{
    public static function disjoint(string $left, string $right): self
    {
        return new self(sprintf('The ranges %s and %s do not overlap, so they have no intersection.', $left, $right));
    }
}
