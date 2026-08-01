<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Runtime;

use PhpArchitecture\LazyOperators\Foundation\Exception\PortNotBoundException;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

/**
 * A runtime-bindable input slot: build it into a tree with no value yet, bind one later via
 * setExpr() (e.g. right before each execution), and it delegates to whatever was bound. Invoking
 * it before binding throws PortNotBoundException. The bound Expression is intentionally not
 * persisted by PortSerializer — a deserialized Port always comes back unbound, ready to be rebound
 * fresh for that execution.
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

    public function setExpr(Expression $expr): void
    {
        $this->expr = $expr;
    }

    public function __invoke(): mixed
    {
        return $this->expr !== null ? ($this->expr)() : throw PortNotBoundException::create($this);
    }
}
