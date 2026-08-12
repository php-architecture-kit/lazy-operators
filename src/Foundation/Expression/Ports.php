<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression;

/**
 * Creates and remembers named Port instances in one place, so the same name → Port mapping used
 * while building an Expression tree can be handed straight to ExpressionTree without keeping a
 * second, hand-written array in sync with it.
 */
final class Ports
{
    /**
     * @var array<string, Port>
     */
    private array $ports = [];

    public function named(string $name): Port
    {
        return $this->ports[$name] ??= new Port($name);
    }

    /**
     * @return array<string, Port>
     */
    public function toArray(): array
    {
        return $this->ports;
    }
}
