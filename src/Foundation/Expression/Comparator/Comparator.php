<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Comparator;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\WrapsRawValues;

class Comparator
{
    use WrapsRawValues;
    use DecoratesNodes;

    private function __construct(
        private readonly Expression $current,
        private readonly ExpressionTreeConfig $config,
    ) {
    }

    public static function of(mixed $value, ?ExpressionTreeConfig $config = null): self
    {
        $config ??= new ExpressionTreeConfig();

        return new self(self::decorate(self::wrap($value), $config), $config);
    }

    public function spaceship(mixed $value): self
    {
        return new self(
            // spaceship's result is always int, so re-expose it as IntegerValue (same as
            // decorateNumber() does for Arithmetic) instead of the generic Expression a plain
            // decorate() would produce once a Decorator is configured. Without this, a decorated
            // spaceship() result could not be fed back into Arithmetic/Logical without a Cast,
            // even though an undecorated one always could (SpaceshipOperator implements
            // IntegerValue unconditionally).
            self::decorateInteger(new SpaceshipOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function build(): Expression
    {
        return $this->current;
    }
}
