<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\DateTimes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

#[Group('Temporal | Literal')]
#[Name('Time Literal')]
#[Formula('f(value) = value as "HH:MM:SS"')]
#[Description('Time Literal is a wall-clock time of day such as "09:00", checked when the node is built and returned as "HH:MM:SS". It has no date and no timezone: it is compared against a date-time\'s own wall clock.')]
final class TimeLiteral implements StringValue
{
    public const KEY = 'time_literal';
    public const UID = 'c6190759-4ba4-4b7b-911f-0eede5d53c84';
    public const VERSION = '1.0';
    private readonly string $normalized;

    public function __construct(
        public readonly string $value,
    ) {
        $this->normalized = DateTimes::normalizeTime($value);
    }

    public function __invoke(): string
    {
        return $this->normalized;
    }
}
