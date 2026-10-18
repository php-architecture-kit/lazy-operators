<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Type\DateTimeValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

#[Group('Temporal | Reading')]
#[Name('Format')]
#[Formula('f(datetime, format) = datetime->format(format)')]
#[Description('Format renders a date-time with a PHP date format ("Y-m-d", "H:i", "l") in its own timezone.')]
final class DateTimeFormatFunction implements StringValue
{
    public const KEY = 'datetime_format';
    public const UID = '5d85fc63-c216-4356-9b85-31b98f233c58';
    public const VERSION = '1.0';

    public function __construct(
        public readonly DateTimeValue $datetime,
        public readonly StringValue $format,
    ) {
    }

    public function __invoke(): string
    {
        return ($this->datetime)()->format(($this->format)());
    }
}
