<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Conditional;

use LogicException;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

class IfBuilder
{
    use WrapsRawValues;
    use DecoratesNodes;

    private function __construct(
        private readonly Expression $condition,
        private readonly PipelineConfig $config,
        private readonly ?Expression $then = null,
        private readonly ?Expression $else = null,
    ) {
    }

    public static function of(mixed $condition, ?PipelineConfig $config = null): self
    {
        $config ??= new PipelineConfig();

        return new self(self::decorate(self::wrap($condition), $config), $config);
    }

    public function then(mixed $value): self
    {
        return new self($this->condition, $this->config, self::decorate(self::wrap($value), $this->config), $this->else);
    }

    public function else(mixed $value): self
    {
        return new self($this->condition, $this->config, $this->then, self::decorate(self::wrap($value), $this->config));
    }

    public function build(): Expression
    {
        if ($this->then === null || $this->else === null) {
            throw new LogicException('IfBuilder requires both then() and else() to be set before build().');
        }

        return self::decorate(new IfElseOperator($this->condition, $this->then, $this->else), $this->config);
    }
}
