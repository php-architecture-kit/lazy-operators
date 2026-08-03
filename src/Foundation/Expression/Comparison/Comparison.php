<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

class Comparison
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

    public function equal(mixed $value): self
    {
        return new self(
            self::decorate(new EqualOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function notEqual(mixed $value): self
    {
        return new self(
            self::decorate(new NotEqualOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function identical(mixed $value): self
    {
        return new self(
            self::decorate(new IdenticalOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function notIdentical(mixed $value): self
    {
        return new self(
            self::decorate(new NotIdenticalOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function greaterThan(mixed $value): self
    {
        return new self(
            self::decorate(new GreaterThanOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function greaterThanOrEqual(mixed $value): self
    {
        return new self(
            self::decorate(new GreaterThanOrEqualOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function lessThan(mixed $value): self
    {
        return new self(
            self::decorate(new LessThanOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function lessThanOrEqual(mixed $value): self
    {
        return new self(
            self::decorate(new LessThanOrEqualOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function build(): Expression
    {
        return $this->current;
    }
}
