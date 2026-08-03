<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression;

use PhpArchitecture\LazyOperators\Foundation\Expression\Exception\PortNotBoundException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

/**
 * A named, runtime-bindable input slot: build it into a tree with no value yet, bind one later
 * via setExpr() (e.g. right before each execution), and it delegates to whatever was bound.
 * Invoking it before binding throws PortNotBoundException. The name identifies this slot among
 * others in the same tree (see ExpressionTree::bind()) — it is not persisted with a bound value,
 * since a deserialized/rebuilt Port always comes back unbound, ready to be rebound fresh for that
 * execution.
 */
#[Group('Runtime')]
#[Name('Port')]
#[Formula('f() = boundExpr()')]
#[Description('Port is a runtime-bindable input slot: throws until setExpr() binds an Expression to it.')]
final class Port implements Expression
{
    public const KEY = 'runtime_port';
    public const UID = 'f823b85c-da49-4f04-8f84-6f755d412e7a';
    public const VERSION = '1.0';
    private ?Expression $expr = null;

    public function __construct(
        public readonly string $name,
    ) {
    }

    public function setExpr(Expression $expr): void
    {
        $this->expr = $expr;
    }

    public function __invoke(): mixed
    {
        return $this->expr !== null ? ($this->expr)() : throw PortNotBoundException::create($this);
    }
}
