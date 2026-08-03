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
            self::decorate(new SpaceshipOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function build(): Expression
    {
        return $this->current;
    }
}
