<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Runtime;

use PhpArchitecture\LazyOperators\Foundation\Expression\Exception\UnknownExpressionTreeInputException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;

/**
 * A thin wrapper around an already-built Expression tree and the named Port inputs found in it,
 * so callers can discover and bind those inputs by name instead of walking the tree themselves.
 */
final readonly class ExpressionTree implements Expression
{
    /**
     * @param array<string, Port> $inputs
     */
    public function __construct(
        private Expression $root,
        private array $inputs,
    ) {
    }

    /**
     * @return string[]
     */
    public function inputNames(): array
    {
        return array_keys($this->inputs);
    }

    public function bind(string $name, Expression $value): self
    {
        $port = $this->inputs[$name] ?? throw UnknownExpressionTreeInputException::create($name);
        $port->setExpr($value);

        return $this;
    }

    public function __invoke(): mixed
    {
        return ($this->root)();
    }
}
