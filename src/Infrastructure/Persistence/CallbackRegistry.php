<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence;

use Closure;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\UnpersistableCallbackException;

final class CallbackRegistry
{
    /** @var array<string, Closure> */
    private array $callbacks = [];

    public function register(string $name, Closure $callback): void
    {
        $this->callbacks[$name] = $callback;
    }

    /**
     * @return string[]
     */
    public function names(): array
    {
        return array_keys($this->callbacks);
    }

    public function nameFor(Closure $callback): string
    {
        foreach ($this->callbacks as $name => $registered) {
            if ($registered === $callback) {
                return $name;
            }
        }

        throw new UnpersistableCallbackException(
            'This closure was never registered via CallbackRegistry::register() and cannot be persisted.',
        );
    }

    public function resolve(string $name): Closure
    {
        return $this->callbacks[$name]
            ?? throw new UnpersistableCallbackException(sprintf('No callback registered under name "%s".', $name));
    }
}
