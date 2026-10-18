<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Cast;

use DateTime;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Cast\DateTimeCast;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\InvalidDateTimeException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\FloatLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\StringLiteral;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DateTimeCastTest extends TestCase
{
    public function testAnIsoStringWithAnOffsetIsPutInTheTimezone(): void
    {
        $cast = new DateTimeCast(new StringLiteral('2026-10-17T15:30:00Z'), new StringLiteral('Europe/Warsaw'));

        self::assertSame('2026-10-17T17:30:00+02:00', $cast()->format(DATE_ATOM));
    }

    public function testADateTimeInterfaceKeepsItsInstant(): void
    {
        $value = new SpyExpression(new DateTime('2026-10-17T08:00:00+00:00'));

        self::assertSame('2026-10-17T10:00:00+02:00', (new DateTimeCast($value, new StringLiteral('Europe/Warsaw')))()->format(DATE_ATOM));
    }

    public function testAUnixTimestampIsRead(): void
    {
        $cast = new DateTimeCast(new IntLiteral(1792245600), new StringLiteral('UTC'));

        self::assertSame('2026-10-17T14:00:00+00:00', $cast()->format(DATE_ATOM));
    }

    public function testATimestampStringIsRead(): void
    {
        $cast = new DateTimeCast(new StringLiteral('@1792245600'), new StringLiteral('UTC'));

        self::assertSame('2026-10-17T14:00:00+00:00', $cast()->format(DATE_ATOM));
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function rejectedStrings(): iterable
    {
        yield 'no offset' => ['2026-10-17T10:00:00'];
        yield 'relative' => ['tomorrow UTC'];
        yield 'time only' => ['10:00+02:00'];
        yield 'garbage' => ['not a date'];
    }

    #[DataProvider('rejectedStrings')]
    public function testStringsThatDependOnTheServerAreRejected(string $value): void
    {
        $this->expectException(InvalidDateTimeException::class);

        (new DateTimeCast(new StringLiteral($value), new StringLiteral('UTC')))();
    }

    public function testAnUnknownTimezoneIsRejected(): void
    {
        $this->expectException(InvalidDateTimeException::class);
        $this->expectExceptionMessage('"Europe/Warszawa" is not a known timezone identifier.');

        (new DateTimeCast(new StringLiteral('2026-10-17T10:00:00Z'), new StringLiteral('Europe/Warszawa')))();
    }

    public function testAnUnsupportedValueIsRejected(): void
    {
        $this->expectException(InvalidDateTimeException::class);

        (new DateTimeCast(new FloatLiteral(1.5), new StringLiteral('UTC')))();
    }
}
