<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

class SwitchBuilder
{
    use WrapsRawValues;
    use DecoratesNodes;

    /**
     * @param CaseOfSwitchCase[] $cases
     */
    private function __construct(
        private readonly Expression $subject,
        private readonly PipelineConfig $config,
        private readonly array $cases = [],
        private readonly ?Expression $default = null,
    ) {
    }

    public static function of(mixed $subject, ?PipelineConfig $config = null): self
    {
        $config ??= new PipelineConfig();

        return new self(self::decorate(self::wrap($subject), $config), $config);
    }

    public function case(mixed $condition, mixed $value): self
    {
        return new self(
            $this->subject,
            $this->config,
            [
                ...$this->cases,
                new CaseOfSwitchCase(
                    self::decorate(self::wrap($condition), $this->config),
                    self::decorate(self::wrap($value), $this->config),
                ),
            ],
            $this->default,
        );
    }

    public function default(mixed $value): self
    {
        return new self($this->subject, $this->config, $this->cases, self::decorate(self::wrap($value), $this->config));
    }

    public function build(): Expression
    {
        return self::decorate(new SwitchCaseOperator($this->subject, $this->cases, $this->default), $this->config);
    }
}
