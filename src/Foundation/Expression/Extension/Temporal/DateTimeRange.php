<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal;

use DateTimeImmutable;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\EmptyRangeException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\InvalidRangeException;

/**
 * A half-open range of time, [start, end): it contains its start but not its end, so adjacent
 * ranges share a boundary without overlapping. A range without an end lasts indefinitely.
 */
final readonly class DateTimeRange
{
    public function __construct(
        public DateTimeImmutable $start,
        public ?DateTimeImmutable $end = null,
    ) {
        if ($end !== null && $end <= $start) {
            throw InvalidRangeException::endNotAfterStart(self::describe($start), self::describe($end));
        }
    }

    public function contains(DateTimeImmutable $dateTime): bool
    {
        return $this->start <= $dateTime && ($this->end === null || $dateTime < $this->end);
    }

    public function overlaps(self $other): bool
    {
        return ($other->end === null || $this->start < $other->end)
            && ($this->end === null || $other->start < $this->end);
    }

    /**
     * @throws EmptyRangeException when the ranges do not overlap
     */
    public function intersection(self $other): self
    {
        if (!$this->overlaps($other)) {
            throw EmptyRangeException::disjoint((string) $this, (string) $other);
        }

        $end = match (true) {
            $this->end === null => $other->end,
            $other->end === null => $this->end,
            default => min($this->end, $other->end),
        };

        return new self(max($this->start, $other->start), $end);
    }

    public function __toString(): string
    {
        return sprintf('[%s, %s)', self::describe($this->start), $this->end === null ? '∞' : self::describe($this->end));
    }

    private static function describe(DateTimeImmutable $dateTime): string
    {
        return $dateTime->format(DATE_ATOM);
    }
}
