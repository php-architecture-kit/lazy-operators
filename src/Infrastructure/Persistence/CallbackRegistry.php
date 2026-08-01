<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence;

use Closure;
use PhpArchitecture\LazyOperators\Application\Registry\CallbackRegistryInterface;
use PhpArchitecture\LazyOperators\Application\Registry\Entry\CallbackDetails;
use PhpArchitecture\LazyOperators\Application\Registry\Entry\CallbackParameter;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\UnpersistableCallbackException;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;
use ReflectionUnionType;

final class CallbackRegistry implements CallbackRegistryInterface
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

    public function getCallbackDetails(string $name): CallbackDetails
    {
        $reflection = new ReflectionFunction($this->resolve($name));

        $parameters = array_map(
            static fn (ReflectionParameter $parameter): CallbackParameter => new CallbackParameter(
                $parameter->getName(),
                self::formatType($parameter->getType()),
            ),
            $reflection->getParameters(),
        );

        $returnType = $reflection->getReturnType();

        return new CallbackDetails(
            name: $name,
            signature: sprintf(
                '(%s)%s',
                implode(', ', array_map(
                    static fn (CallbackParameter $parameter): string => "{$parameter->type} \${$parameter->name}",
                    $parameters,
                )),
                $returnType === null ? '' : ': ' . self::formatType($returnType),
            ),
            parameters: $parameters,
            returnType: $returnType === null ? null : self::formatType($returnType),
        );
    }

    private static function formatType(?ReflectionType $type): string
    {
        return match (true) {
            $type === null => 'mixed',
            $type instanceof ReflectionNamedType => ($type->allowsNull() && $type->getName() !== 'null' ? '?' : '') . $type->getName(),
            $type instanceof ReflectionUnionType => implode('|', array_map(self::formatType(...), $type->getTypes())),
            default => (string) $type,
        };
    }
}
