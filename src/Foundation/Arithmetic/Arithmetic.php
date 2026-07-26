<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

class Arithmetic
{
    use WrapsRawValues;

    private function __construct(
        private readonly Expression $current,
    ) {
    }

    public static function of(int|float|Expression $value): self
    {
        return new self(self::wrap($value));
    }

    public function add(int|float|Expression $value): self
    {
        return new self(new AdditionOperator($this->current, self::wrap($value)));
    }

    public function subtract(int|float|Expression $value): self
    {
        return new self(new SubtractionOperator($this->current, self::wrap($value)));
    }

    public function multiply(int|float|Expression $value): self
    {
        return new self(new MultiplicationOperator($this->current, self::wrap($value)));
    }

    public function divide(int|float|Expression $value): self
    {
        return new self(new DivisionOperator($this->current, self::wrap($value)));
    }

    public function modulo(int|float|Expression $value): self
    {
        return new self(new ModuloOperator($this->current, self::wrap($value)));
    }

    public function power(int|float|Expression $value): self
    {
        return new self(new ExponentiationOperator($this->current, self::wrap($value)));
    }

    public function build(): Expression
    {
        return $this->current;
    }
}
