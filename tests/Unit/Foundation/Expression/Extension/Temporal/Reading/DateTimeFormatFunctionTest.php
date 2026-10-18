<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Reading;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading\DateTimeFormatFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\StringLiteral;
use PHPUnit\Framework\TestCase;

final class DateTimeFormatFunctionTest extends TestCase
{
    public function testItFormatsInTheDateTimesTimezone(): void
    {
        $format = new DateTimeFormatFunction(new DateTimeLiteral('2026-10-17T23:30:00Z', 'Europe/Warsaw'), new StringLiteral('l Y-m-d H:i'));

        self::assertSame('Sunday 2026-10-18 01:30', $format());
    }
}
