<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

class Logical
{
    use WrapsRawValues;

    private function __construct(
        private readonly Expression $current,
    ) {
    }

    public static function of(bool|Expression $value): self
    {
        return new self(self::wrap($value));
    }

    public function and(bool|Expression $value): self
    {
        return new self(new AndOperator($this->current, self::wrap($value)));
    }

    public function or(bool|Expression $value): self
    {
        return new self(new OrOperator($this->current, self::wrap($value)));
    }

    public function xor(bool|Expression $value): self
    {
        return new self(new XorOperator($this->current, self::wrap($value)));
    }

    public function not(): self
    {
        return new self(new NotOperator($this->current));
    }

    public function build(): Expression
    {
        return $this->current;
    }
}
