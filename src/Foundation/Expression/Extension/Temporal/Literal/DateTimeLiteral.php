<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Support\DateTimes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Temporal | Literal')]
#[Name('Date-Time Literal')]
#[Formula('f(value, timezone) = value in timezone')]
#[Description('Date-Time Literal is a fixed point in time, written as an ISO 8601 string with an offset (e.g. "2026-12-31T23:59:59+01:00") and placed in an IANA timezone. Both are checked when the node is built.')]
final class DateTimeLiteral implements DateTimeValue
{
    public const KEY = 'datetime_literal';
    public const UID = '40addcd8-3e28-45df-8f80-21ecb51752a8';
    public const VERSION = '1.0';
    private readonly DateTimeImmutable $dateTime;

    public function __construct(
        public readonly string $value,
        public readonly string $timezone,
    ) {
        $this->dateTime = DateTimes::read($value, DateTimes::zone($timezone));
    }

    public function __invoke(): DateTimeImmutable
    {
        return $this->dateTime;
    }
}
