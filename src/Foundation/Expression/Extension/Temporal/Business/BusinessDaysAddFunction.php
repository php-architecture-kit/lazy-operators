<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Business;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\BusinessCalendar;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\ArrayValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\ItemTypeOf;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\IntegerValue;

#[Group('Temporal | Business days')]
#[Name('Add Business Days')]
#[Formula('f(datetime, days, holidays, weekend) = the days-th business day after datetime')]
#[Description('Add Business Days moves forward (or back, for a negative count) by business days, keeping the wall-clock time. The starting day never counts, so from a Saturday the first business day is Monday; zero returns the date-time unchanged. Holidays are "YYYY-MM-DD" dates and the weekend is a list of ISO days (1 = Monday), Saturday and Sunday by default, both read in the date-time\'s own timezone.')]
final class BusinessDaysAddFunction implements DateTimeValue
{
    public const KEY = 'business_days_add';
    public const UID = '7d9512e2-8a51-4eec-8648-d8c87f67af06';
    public const VERSION = '1.0';

    /**
     * @param ArrayValue<mixed> $holidays
     * @param ArrayValue<mixed>|null $weekend
     */
    public function __construct(
        public readonly DateTimeValue $datetime,
        public readonly IntegerValue $days,
        #[ItemTypeOf('string')]
        public readonly ArrayValue $holidays,
        #[ItemTypeOf('int')]
        public readonly ?ArrayValue $weekend = null,
    ) {
    }

    public function __invoke(): DateTimeImmutable
    {
        return $this->calendar()->addBusinessDays(($this->datetime)(), ($this->days)());
    }

    private function calendar(): BusinessCalendar
    {
        return BusinessCalendar::from(($this->holidays)(), $this->weekend === null ? null : ($this->weekend)());
    }
}
