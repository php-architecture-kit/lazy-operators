<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\IntegerValue;

#[Group('Temporal | Reading')]
#[Name('Timestamp')]
#[Formula('f(datetime) = seconds since 1970-01-01T00:00:00Z')]
#[Description('Timestamp turns a date-time into a Unix timestamp, so it can feed arithmetic and comparison operators. The timezone does not change it.')]
final class DateTimeTimestampFunction implements IntegerValue
{
    public const KEY = 'datetime_timestamp';
    public const UID = '571eac58-d473-4a76-b9ed-54084feafb7d';
    public const VERSION = '1.0';

    public function __construct(
        public readonly DateTimeValue $datetime,
    ) {
    }

    public function __invoke(): int
    {
        return ($this->datetime)()->getTimestamp();
    }
}
