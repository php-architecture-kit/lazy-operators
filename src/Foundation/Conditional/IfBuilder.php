<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Conditional;

use LogicException;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

class IfBuilder
{
    use WrapsRawValues;

    private function __construct(
        private readonly Expression $condition,
        private readonly ?Expression $then = null,
        private readonly ?Expression $else = null,
    ) {
    }

    public static function of(mixed $condition): self
    {
        return new self(self::wrap($condition));
    }

    public function then(mixed $value): self
    {
        return new self($this->condition, self::wrap($value), $this->else);
    }

    public function else(mixed $value): self
    {
        return new self($this->condition, $this->then, self::wrap($value));
    }

    public function build(): Expression
    {
        if ($this->then === null || $this->else === null) {
            throw new LogicException('IfBuilder requires both then() and else() to be set before build().');
        }

        return new IfElseOperator($this->condition, $this->then, $this->else);
    }
}
