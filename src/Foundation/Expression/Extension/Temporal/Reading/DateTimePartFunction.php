<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DatePart;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\IntegerValue;

#[Group('Temporal | Reading')]
#[Name('Part')]
#[Formula('f(datetime, part) = the part of datetime\'s wall clock, as an integer')]
#[Description('Part reads one field of the date-time\'s wall clock in its own timezone: Year, Month, Day, Hour, Minute, Second, DayOfWeek (1 = Monday), DayOfYear, IsoWeek or SecondOfDay (12:00 = 43200).')]
final class DateTimePartFunction implements IntegerValue
{
    public const KEY = 'datetime_part';
    public const UID = '4a7bb939-0d8c-4613-977e-890a1583489d';
    public const VERSION = '1.0';

    public function __construct(
        public readonly DateTimeValue $datetime,
        public readonly DatePart $part,
    ) {
    }

    public function __invoke(): int
    {
        return $this->part->read(($this->datetime)());
    }
}
