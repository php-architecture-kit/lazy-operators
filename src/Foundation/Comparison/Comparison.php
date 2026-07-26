<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

class Comparison
{
    use WrapsRawValues;

    private function __construct(
        private readonly Expression $current,
    ) {
    }

    public static function of(mixed $value): self
    {
        return new self(self::wrap($value));
    }

    public function equal(mixed $value): self
    {
        return new self(new EqualOperator($this->current, self::wrap($value)));
    }

    public function notEqual(mixed $value): self
    {
        return new self(new NotEqualOperator($this->current, self::wrap($value)));
    }

    public function identical(mixed $value): self
    {
        return new self(new IdenticalOperator($this->current, self::wrap($value)));
    }

    public function notIdentical(mixed $value): self
    {
        return new self(new NotIdenticalOperator($this->current, self::wrap($value)));
    }

    public function greaterThan(mixed $value): self
    {
        return new self(new GreaterThanOperator($this->current, self::wrap($value)));
    }

    public function greaterThanOrEqual(mixed $value): self
    {
        return new self(new GreaterThanOrEqualOperator($this->current, self::wrap($value)));
    }

    public function lessThan(mixed $value): self
    {
        return new self(new LessThanOperator($this->current, self::wrap($value)));
    }

    public function lessThanOrEqual(mixed $value): self
    {
        return new self(new LessThanOrEqualOperator($this->current, self::wrap($value)));
    }

    public function build(): Expression
    {
        return $this->current;
    }
}
