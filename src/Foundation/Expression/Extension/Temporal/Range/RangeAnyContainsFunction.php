<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Range;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\RangeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;

#[Group('Temporal | Range')]
#[Name('Any Contains')]
#[Formula('f(datetime, first, ...rest) = any range contains datetime')]
#[Description('Any Contains checks a date-time against several ranges at once, for validity with gaps (valid in January and in March).')]
final class RangeAnyContainsFunction implements BooleanValue
{
    public const KEY = 'range_any_contains';
    public const UID = '4d31d617-1358-4f2c-b20a-eb5c00f75c85';
    public const VERSION = '1.0';

    /**
     * @var RangeValue[]
     */
    public readonly array $rest;

    public function __construct(
        public readonly DateTimeValue $datetime,
        public readonly RangeValue $first,
        RangeValue ...$rest,
    ) {
        $this->rest = $rest;
    }

    public function __invoke(): bool
    {
        $dateTime = ($this->datetime)();

        foreach ([$this->first, ...$this->rest] as $range) {
            if ($range()->contains($dateTime)) {
                return true;
            }
        }

        return false;
    }
}
