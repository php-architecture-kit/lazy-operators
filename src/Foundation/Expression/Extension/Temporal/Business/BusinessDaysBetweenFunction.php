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
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\IntegerValue;

#[Group('Temporal | Business days')]
#[Name('Business Days Between')]
#[Formula('f(from, to, holidays, weekend) = business days after from, up to and including to')]
#[Description('Business Days Between counts the business days after from up to and including to, negative when to is earlier, so it undoes Add Business Days. Days are compared in from\'s timezone. Holidays are "YYYY-MM-DD" dates and the weekend is a list of ISO days (1 = Monday), Saturday and Sunday by default, both read in the date-time\'s own timezone.')]
final class BusinessDaysBetweenFunction implements IntegerValue
{
    public const KEY = 'business_days_between';
    public const UID = 'cf964c02-eb8c-4212-bd77-82a0ba3f6677';
    public const VERSION = '1.0';

    /**
     * @param ArrayValue<mixed> $holidays
     * @param ArrayValue<mixed>|null $weekend
     */
    public function __construct(
        public readonly DateTimeValue $from,
        public readonly DateTimeValue $to,
        #[ItemTypeOf('string')]
        public readonly ArrayValue $holidays,
        #[ItemTypeOf('int')]
        public readonly ?ArrayValue $weekend = null,
    ) {
    }

    public function __invoke(): int
    {
        return $this->calendar()->businessDaysBetween(($this->from)(), ($this->to)());
    }

    private function calendar(): BusinessCalendar
    {
        return BusinessCalendar::from(($this->holidays)(), $this->weekend === null ? null : ($this->weekend)());
    }
}
