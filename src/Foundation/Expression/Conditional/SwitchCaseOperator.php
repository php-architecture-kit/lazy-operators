<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Conditional\Exception\NoMatchedCaseException;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\ItemTypeOf;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Group('Conditional')]
#[Name('Switch Case')]
#[Formula('f(condition, cases, default) = value of the first case in cases whose condition = condition, otherwise default')]
#[Description('Switch Case compares a condition against a list of cases in order and returns the first matching case\'s value, or the default value when no case matches.')]
class SwitchCaseOperator implements Expression
{
    public const KEY = 'switch_case';
    public const UID = '70ac9fb9-bfa7-4080-9a3d-c9182ed50529';
    public const VERSION = '1.0';

    /**
     * @param CaseOfSwitchCase[] $cases
     */
    public function __construct(
        public readonly Expression $condition,
        #[ItemTypeOf('CaseOfSwitchCase')]
        public readonly array $cases,
        public readonly ?Expression $default = null,
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
            : throw NoMatchedCaseException::create($conditionValue);
    }
}
