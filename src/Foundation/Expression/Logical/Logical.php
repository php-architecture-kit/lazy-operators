<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\WrapsRawValues;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;

class Logical
{
    use WrapsRawValues;
    use DecoratesNodes;

    private function __construct(
        private readonly BooleanValue $current,
        private readonly ExpressionTreeConfig $config,
    ) {}

    public static function of(bool|BooleanValue $value, ?ExpressionTreeConfig $config = null): self
    {
        $config ??= new ExpressionTreeConfig();

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

    private static function wrapDecorate(bool|BooleanValue $value, ExpressionTreeConfig $config): BooleanValue
    {
        return self::decorateBoolean(self::wrapAs(BooleanValue::class, $value), $config);
    }
}
