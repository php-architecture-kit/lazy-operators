<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception\IncompleteIfBuilderException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\WrapsRawValues;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

class IfBuilder
{
    use WrapsRawValues;
    use DecoratesNodes;

    private function __construct(
        private readonly BooleanValue $condition,
        private readonly ExpressionTreeConfig $config,
        private readonly ?Expression $then = null,
        private readonly ?Expression $else = null,
    ) {
    }

    public static function of(bool|BooleanValue $condition, ?ExpressionTreeConfig $config = null): self
    {
        $config ??= new ExpressionTreeConfig();

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

        // A plain `new IfElseOperator(...)` can only ever implement the generic Expression contract:
        // a single class can't conditionally implement NumberValue/BooleanValue/StringValue based on
        // what a *given instance's* branches happen to be. But when both branches already share the
        // same typed contract, building the matching narrow subclass instead (still a genuine
        // IfElseOperator, via inheritance) lets callers feed the result straight into
        // Arithmetic/Logical without an explicit Cast. Passing the result through decorateNumber()/
        // decorateBoolean()/decorateString() afterwards keeps Decorator support working exactly as
        // before: undecorated, they're a no-op since the node already implements the right interface;
        // decorated, they fall back to re-exposing via the same DecoratedNumberValue-style wrapper
        // Arithmetic already relies on.
        $node = match (true) {
            $this->then instanceof BooleanValue && $this->else instanceof BooleanValue => new BooleanIfElseOperator($this->condition, $this->then, $this->else),
            $this->then instanceof NumberValue && $this->else instanceof NumberValue => new NumberIfElseOperator($this->condition, $this->then, $this->else),
            $this->then instanceof StringValue && $this->else instanceof StringValue => new StringIfElseOperator($this->condition, $this->then, $this->else),
            default => new IfElseOperator($this->condition, $this->then, $this->else),
        };

        return match (true) {
            $node instanceof BooleanIfElseOperator => self::decorateBoolean($node, $this->config),
            $node instanceof NumberIfElseOperator => self::decorateNumber($node, $this->config),
            $node instanceof StringIfElseOperator => self::decorateString($node, $this->config),
            default => self::decorate($node, $this->config),
        };
    }
}
