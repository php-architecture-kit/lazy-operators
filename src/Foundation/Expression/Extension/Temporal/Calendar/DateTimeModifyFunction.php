<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Calendar;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\DateTimes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

#[Group('Temporal | Calendar')]
#[Name('Modify')]
#[Formula('f(datetime, modifier) = datetime->modify(modifier)')]
#[Description('Modify applies a PHP relative date-time format in the date-time\'s own timezone: "+7 days", "+7 weekdays", "midnight", "last day of next month", "next monday". Note PHP\'s own rules: "+1 month" on 31 January lands on 3 March, and "weekdays" knows no holidays.')]
final class DateTimeModifyFunction implements DateTimeValue
{
    public const KEY = 'datetime_modify';
    public const UID = '7cfc7899-024e-49e4-96ea-902ae549187d';
    public const VERSION = '1.0';

    public function __construct(
        public readonly DateTimeValue $datetime,
        public readonly StringValue $modifier,
    ) {
    }

    public function __invoke(): DateTimeImmutable
    {
        return DateTimes::modify(($this->datetime)(), ($this->modifier)());
    }
}
