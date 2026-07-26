<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

class SwitchBuilder
{
    use WrapsRawValues;

    /**
     * @param CaseOfSwitchCase[] $cases
     */
    private function __construct(
        private readonly Expression $subject,
        private readonly array $cases = [],
        private readonly ?Expression $default = null,
    ) {
    }

    public static function of(mixed $subject): self
    {
        return new self(self::wrap($subject));
    }

    public function case(mixed $condition, mixed $value): self
    {
        return new self(
            $this->subject,
            [...$this->cases, new CaseOfSwitchCase(self::wrap($condition), self::wrap($value))],
            $this->default,
        );
    }

    public function default(mixed $value): self
    {
        return new self($this->subject, $this->cases, self::wrap($value));
    }

    public function build(): Expression
    {
        return new SwitchCaseOperator($this->subject, $this->cases, $this->default);
    }
}
