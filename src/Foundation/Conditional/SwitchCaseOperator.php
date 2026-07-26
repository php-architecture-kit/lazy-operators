<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Conditional\Exception\NoMatchedCaseException;
use PhpArchitecture\LazyOperators\Foundation\Expression;

class SwitchCaseOperator implements Expression
{
    /**
     * @param CaseOfSwitchCase[] $cases
     */
    public function __construct(
        private readonly Expression $condition,
        private readonly array $cases,
        private readonly ?Expression $default = null,
    ) {}

    public function __invoke(): mixed
    {
        $conditionValue = ($this->condition)();
        foreach ($this->cases as $case) {
            if (($case->condition)() === $conditionValue) {
                return ($case->value)();
            }
        }

        return $this->default
            ? ($this->default)()
            : throw new NoMatchedCaseException();
    }
}
