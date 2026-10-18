<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;

/**
 * Re-exposes an already-decorated node as a date-time, so a decorated temporal pipeline can keep
 * chaining into the next node's narrowed constructor (see DecoratesNodes).
 */
final class DecoratedDateTimeValue implements DateTimeValue
{
    public function __construct(
        private readonly Expression $decorated,
    ) {
    }

    public function __invoke(): DateTimeImmutable
    {
        $value = ($this->decorated)();
        assert($value instanceof DateTimeImmutable);

        return $value;
    }
}
