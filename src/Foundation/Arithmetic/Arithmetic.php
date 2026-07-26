<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

class Arithmetic
{
    use WrapsRawValues;
    use DecoratesNodes;

    private function __construct(
        private readonly Expression $current,
        private readonly PipelineConfig $config,
    ) {
    }

    public static function of(int|float|Expression $value, ?PipelineConfig $config = null): self
    {
        $config ??= new PipelineConfig();

        return new self(self::decorate(self::wrap($value), $config), $config);
    }

    public function add(int|float|Expression $value): self
    {
        return new self(
            self::decorate(new AdditionOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function subtract(int|float|Expression $value): self
    {
        return new self(
            self::decorate(new SubtractionOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function multiply(int|float|Expression $value): self
    {
        return new self(
            self::decorate(new MultiplicationOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function divide(int|float|Expression $value): self
    {
        return new self(
            self::decorate(new DivisionOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function modulo(int|float|Expression $value): self
    {
        return new self(
            self::decorate(new ModuloOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function power(int|float|Expression $value): self
    {
        return new self(
            self::decorate(new ExponentiationOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function build(): Expression
    {
        return $this->current;
    }
}
