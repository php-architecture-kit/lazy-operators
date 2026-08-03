<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Conditional\Exception;

use RuntimeException;

final class NoMatchedCaseException extends RuntimeException implements LazyOperatorsConditionalException
{
    public static function create(mixed $conditionValue): self
    {
        return new self(sprintf(
            'No case matched value of type %s (%s).',
            get_debug_type($conditionValue),
            var_export($conditionValue, true),
        ));
    }
}
