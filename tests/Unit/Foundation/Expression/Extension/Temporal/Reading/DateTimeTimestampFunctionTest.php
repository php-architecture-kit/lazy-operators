<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Reading;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\DateTimeLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Reading\DateTimeTimestampFunction;
use PHPUnit\Framework\TestCase;

final class DateTimeTimestampFunctionTest extends TestCase
{
    public function testTheTimezoneDoesNotChangeTheTimestamp(): void
    {
        self::assertSame(1792245600, (new DateTimeTimestampFunction(new DateTimeLiteral('2026-10-17T14:00:00Z', 'Europe/Warsaw')))());
        self::assertSame(1792245600, (new DateTimeTimestampFunction(new DateTimeLiteral('2026-10-17T14:00:00Z', 'Asia/Tokyo')))());
    }
}
