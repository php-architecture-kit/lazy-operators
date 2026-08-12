<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\WrapsRawValues;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

class SwitchBuilder
{
    use WrapsRawValues;
    use DecoratesNodes;

    /**
     * @param CaseOfSwitchCase[] $cases
     */
    private function __construct(
        private readonly Expression $subject,
        private readonly ExpressionTreeConfig $config,
        private readonly array $cases = [],
        private readonly ?Expression $default = null,
    ) {
    }

    public static function of(mixed $subject, ?ExpressionTreeConfig $config = null): self
    {
        $config ??= new ExpressionTreeConfig();

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
        $values = array_map(static fn (CaseOfSwitchCase $case): Expression => $case->value, $this->cases);
        if ($this->default !== null) {
            $values[] = $this->default;
        }

        // See IfBuilder::build() for the same reasoning: build the narrow subclass (still a genuine
        // SwitchCaseOperator, via inheritance) when every possible branch — each case's value, plus
        // default if present — already shares the same typed contract, so callers can feed the
        // result straight into Arithmetic/Logical without an explicit Cast.
        $node = match (true) {
            $values !== [] && self::allInstanceOf($values, BooleanValue::class) => new BooleanSwitchCaseOperator($this->subject, $this->cases, $this->default),
            $values !== [] && self::allInstanceOf($values, NumberValue::class) => new NumberSwitchCaseOperator($this->subject, $this->cases, $this->default),
            $values !== [] && self::allInstanceOf($values, StringValue::class) => new StringSwitchCaseOperator($this->subject, $this->cases, $this->default),
            default => new SwitchCaseOperator($this->subject, $this->cases, $this->default),
        };

        return match (true) {
            $node instanceof BooleanSwitchCaseOperator => self::decorateBoolean($node, $this->config),
            $node instanceof NumberSwitchCaseOperator => self::decorateNumber($node, $this->config),
            $node instanceof StringSwitchCaseOperator => self::decorateString($node, $this->config),
            default => self::decorate($node, $this->config),
        };
    }

    /**
     * @param Expression[] $values
     * @param class-string  $type
     */
    private static function allInstanceOf(array $values, string $type): bool
    {
        foreach ($values as $value) {
            if (!$value instanceof $type) {
                return false;
            }
        }

        return true;
    }
}
