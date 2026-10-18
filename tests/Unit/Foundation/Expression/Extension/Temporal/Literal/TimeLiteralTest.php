<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Literal;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\InvalidDateTimeException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Literal\TimeLiteral;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TimeLiteralTest extends TestCase
{
    public function testItIsNormalizedToHoursMinutesAndSeconds(): void
    {
        self::assertSame('09:00:00', (new TimeLiteral('9:00'))());
        self::assertSame('17:30:15', (new TimeLiteral('17:30:15'))());
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function invalidTimes(): iterable
    {
        yield 'hour 24' => ['24:00'];
        yield 'minute 60' => ['09:60'];
        yield 'no minutes' => ['9'];
        yield 'with a zone' => ['09:00+02:00'];
    }

    #[DataProvider('invalidTimes')]
    public function testAnInvalidTimeIsRejectedWhenBuilt(string $value): void
    {
        $this->expectException(InvalidDateTimeException::class);

        new TimeLiteral($value);
    }
}
