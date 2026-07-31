<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;

class Arithmetic
{
    use WrapsRawValues;
    use DecoratesNodes;

    private function __construct(
        private readonly Expression&NumberValue $current,
        private readonly PipelineConfig $config,
    ) {
    }

    public static function of(int|float|(Expression&NumberValue) $value, ?PipelineConfig $config = null): self
    {
        $config ??= new PipelineConfig();

        return new self(self::decorateNumeric($value, $config), $config);
    }

    public function add(int|float|(Expression&NumberValue) $value): self
    {
        return new self(
            self::decorateOperator(new AdditionOperator($this->current, self::decorateNumeric($value, $this->config)), $this->config),
            $this->config,
        );
    }

    public function subtract(int|float|(Expression&NumberValue) $value): self
    {
        return new self(
            self::decorateOperator(new SubtractionOperator($this->current, self::decorateNumeric($value, $this->config)), $this->config),
            $this->config,
        );
    }

    public function multiply(int|float|(Expression&NumberValue) $value): self
    {
        return new self(
            self::decorateOperator(new MultiplicationOperator($this->current, self::decorateNumeric($value, $this->config)), $this->config),
            $this->config,
        );
    }

    public function divide(int|float|(Expression&NumberValue) $value): self
    {
        return new self(
            self::decorateOperator(new DivisionOperator($this->current, self::decorateNumeric($value, $this->config)), $this->config),
            $this->config,
        );
    }

    public function modulo(int|float|(Expression&NumberValue) $value): self
    {
        return new self(
            self::decorateOperator(new ModuloOperator($this->current, self::decorateNumeric($value, $this->config)), $this->config),
            $this->config,
        );
    }

    public function power(int|float|(Expression&NumberValue) $value): self
    {
        return new self(
            self::decorateOperator(new ExponentiationOperator($this->current, self::decorateNumeric($value, $this->config)), $this->config),
            $this->config,
        );
    }

    public function build(): Expression&NumberValue
    {
        return $this->current;
    }

    private static function decorateNumeric(int|float|(Expression&NumberValue) $value, PipelineConfig $config): Expression&NumberValue
    {
        return self::decorateNumber(self::wrapAs(NumberValue::class, $value), $config);
    }

    private static function decorateOperator(Expression&NumberValue $node, PipelineConfig $config): Expression&NumberValue
    {
        return self::decorateNumber($node, $config);
    }
}
