<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Cast;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\DateTimes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

#[Group('Temporal | Cast')]
#[Name('Date-Time Cast')]
#[Formula('f(value, timezone) = value as a date-time in timezone')]
#[Description('Date-Time Cast reads a DateTimeInterface, an ISO 8601 string with an offset, or a Unix timestamp, and puts it in the given IANA timezone. Every later calendar and wall-clock operation runs in that timezone. Strings without an offset and relative strings ("tomorrow") are rejected.')]
final class DateTimeCast implements DateTimeValue
{
    public const KEY = 'cast_datetime';
    public const UID = 'ccdfc939-e27b-461d-a0a1-b439114a06f7';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $value,
        public readonly StringValue $timezone,
    ) {
    }

    public function __invoke(): DateTimeImmutable
    {
        return DateTimes::read(($this->value)(), DateTimes::zone(($this->timezone)()));
    }
}
