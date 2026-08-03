<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception\IncompleteIfBuilderException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\WrapsRawValues;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;

class IfBuilder
{
    use WrapsRawValues;
    use DecoratesNodes;

    private function __construct(
        private readonly BooleanValue $condition,
        private readonly PipelineConfig $config,
        private readonly ?Expression $then = null,
        private readonly ?Expression $else = null,
    ) {
    }

    public static function of(bool|BooleanValue $condition, ?PipelineConfig $config = null): self
    {
        $config ??= new PipelineConfig();

        return new self(self::decorateBoolean(self::wrapAs(BooleanValue::class, $condition), $config), $config);
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
            throw IncompleteIfBuilderException::create($this->then === null, $this->else === null);
        }

        return self::decorate(new IfElseOperator($this->condition, $this->then, $this->else), $this->config);
    }
}
