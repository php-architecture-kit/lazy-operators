<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparator;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

class Comparator
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

    public function spaceship(mixed $value): self
    {
        return new self(new SpaceshipOperator($this->current, self::wrap($value)));
    }

    public function build(): Expression
    {
        return $this->current;
    }
}
