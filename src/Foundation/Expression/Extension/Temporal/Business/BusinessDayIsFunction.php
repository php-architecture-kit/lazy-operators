<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Business;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\BusinessCalendar;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\ArrayValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\ItemTypeOf;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;

#[Group('Temporal | Business days')]
#[Name('Is Business Day')]
#[Formula('f(datetime, holidays, weekend) = the day of datetime is neither a weekend day nor a holiday')]
#[Description('Is Business Day checks whether the date-time falls on a business day. Holidays are "YYYY-MM-DD" dates and the weekend is a list of ISO days (1 = Monday), Saturday and Sunday by default, both read in the date-time\'s own timezone.')]
final class BusinessDayIsFunction implements BooleanValue
{
    public const KEY = 'business_day_is';
    public const UID = '21c2bd77-3eaf-434e-9d06-ffa015f89fa2';
    public const VERSION = '1.0';

    /**
     * @param ArrayValue<mixed> $holidays
     * @param ArrayValue<mixed>|null $weekend
     */
    public function __construct(
        public readonly DateTimeValue $datetime,
        #[ItemTypeOf('string')]
        public readonly ArrayValue $holidays,
        #[ItemTypeOf('int')]
        public readonly ?ArrayValue $weekend = null,
    ) {
    }

    public function __invoke(): bool
    {
        return $this->calendar()->isBusinessDay(($this->datetime)());
    }

    private function calendar(): BusinessCalendar
    {
        return BusinessCalendar::from(($this->holidays)(), $this->weekend === null ? null : ($this->weekend)());
    }
}
