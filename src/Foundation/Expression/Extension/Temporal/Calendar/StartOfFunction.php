<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Calendar;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\CalendarUnit;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\WallClock;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Temporal | Calendar')]
#[Name('Start Of')]
#[Formula('f(datetime, unit) = the first instant of the day, week, month or year containing datetime')]
#[Description('Start Of returns the first real instant of the day, ISO week (Monday), month or year the date-time falls in, in its own timezone. Where midnight is skipped or repeated by a clock change, that is the first instant the day actually has.')]
final class StartOfFunction implements DateTimeValue
{
    public const KEY = 'start_of';
    public const UID = 'd27d7857-17ae-445c-9dd4-aab472fbf4a5';
    public const VERSION = '1.0';

    public function __construct(
        public readonly DateTimeValue $datetime,
        public readonly CalendarUnit $unit,
    ) {
    }

    public function __invoke(): DateTimeImmutable
    {
        return WallClock::startOf(($this->datetime)(), $this->unit);
    }
}
