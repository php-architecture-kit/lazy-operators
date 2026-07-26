<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

class Logical
{
    use WrapsRawValues;
    use DecoratesNodes;

    private function __construct(
        private readonly Expression $current,
        private readonly PipelineConfig $config,
    ) {
    }

    public static function of(bool|Expression $value, ?PipelineConfig $config = null): self
    {
        $config ??= new PipelineConfig();

        return new self(self::decorate(self::wrap($value), $config), $config);
    }

    public function and(bool|Expression $value): self
    {
        return new self(
            self::decorate(new AndOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function or(bool|Expression $value): self
    {
        return new self(
            self::decorate(new OrOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function xor(bool|Expression $value): self
    {
        return new self(
            self::decorate(new XorOperator($this->current, self::decorate(self::wrap($value), $this->config)), $this->config),
            $this->config,
        );
    }

    public function not(): self
    {
        return new self(self::decorate(new NotOperator($this->current), $this->config), $this->config);
    }

    public function build(): Expression
    {
        return $this->current;
    }
}
