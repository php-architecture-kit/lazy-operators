<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparator;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

class Comparator
{
    use WrapsRawValues;
    use DecoratesNodes;

    private function __construct(
        private readonly Expression $current,
        private readonly PipelineConfig $config,
    ) {
    }

    public static function of(mixed $value, ?PipelineConfig $config = null): self
    {
        $config ??= new PipelineConfig();

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
