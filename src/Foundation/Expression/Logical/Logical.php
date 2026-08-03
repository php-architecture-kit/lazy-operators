<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;

class Logical
{
    use WrapsRawValues;
    use DecoratesNodes;

    private function __construct(
        private readonly BooleanValue $current,
        private readonly PipelineConfig $config,
    ) {}

    public static function of(bool|BooleanValue $value, ?PipelineConfig $config = null): self
    {
        $config ??= new PipelineConfig();

        return new self(self::wrapDecorate($value, $config), $config);
    }

    public function and(bool|BooleanValue $value): self
    {
        return new self(
            self::decorateBoolean(new AndOperator($this->current, self::wrapDecorate($value, $this->config)), $this->config),
            $this->config,
        );
    }

    public function or(bool|BooleanValue $value): self
    {
        return new self(
            self::decorateBoolean(new OrOperator($this->current, self::wrapDecorate($value, $this->config)), $this->config),
            $this->config,
        );
    }

    public function xor(bool|BooleanValue $value): self
    {
        return new self(
            self::decorateBoolean(new XorOperator($this->current, self::wrapDecorate($value, $this->config)), $this->config),
            $this->config,
        );
    }

    public function not(): self
    {
        return new self(self::decorateBoolean(new NotOperator($this->current), $this->config), $this->config);
    }

    public function build(): BooleanValue
    {
        return $this->current;
    }

    private static function wrapDecorate(bool|BooleanValue $value, PipelineConfig $config): BooleanValue
    {
        return self::decorateBoolean(self::wrapAs(BooleanValue::class, $value), $config);
    }
}
