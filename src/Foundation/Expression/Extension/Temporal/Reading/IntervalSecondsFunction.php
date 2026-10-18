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
#[Name('Seconds Between')]
#[Formula('f(from, to) = timestamp(to) - timestamp(from)')]
#[Description('Seconds Between returns the exact elapsed seconds from one date-time to another (negative when to is earlier); a day with a clock change counts its real 23 or 25 hours.')]
final class IntervalSecondsFunction implements IntegerValue
{
    public const KEY = 'interval_seconds';
    public const UID = '65388831-59ec-4401-9fe9-29fc1432ce50';
    public const VERSION = '1.0';

    public function __construct(
        public readonly DateTimeValue $from,
        public readonly DateTimeValue $to,
    ) {
    }

    public function __invoke(): int
    {
        return ($this->to)()->getTimestamp() - ($this->from)()->getTimestamp();
    }
}
