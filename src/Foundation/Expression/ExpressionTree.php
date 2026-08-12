<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression;

use PhpArchitecture\LazyOperators\Foundation\Expression\Exception\UnknownExpressionTreeInputException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;

/**
 * A thin wrapper around an already-built Expression tree and the named Port inputs found in it,
 * so callers can discover and bind those inputs by name instead of walking the tree themselves.
 */
final readonly class ExpressionTree implements Expression
{
    /**
     * @var array<string, Port>
     */
    private array $inputs;

    /**
     * @param array<string, Port>|Ports $inputs Pass a Ports instance built alongside the tree
     * (via Ports::named()) to avoid keeping a second, hand-written name => Port map in sync with
     * it; a plain array is still accepted for callers who already track their Ports another way.
     */
    public function __construct(
        private Expression $root,
        array|Ports $inputs,
    ) {
        $this->inputs = $inputs instanceof Ports ? $inputs->toArray() : $inputs;
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
