<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Static;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Static\Value;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\UnpersistableValueException;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class ValueSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof Value;
    }

    public function expressionVersion(): string
    {
        return Value::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof Value);

        $this->assertJsonSafe($expression->value);

        return [
            'uid' => Value::UID,
            'key' => Value::KEY,
            'class' => Value::class,
            'version' => Value::VERSION,
            'args' => [$expression->value],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new Value($data['args'][0]);
    }

    private function assertJsonSafe(mixed $value): void
    {
        match (true) {
            $value === null, is_bool($value), is_int($value), is_float($value), is_string($value) => null,
            is_array($value) => array_walk($value, fn (mixed $item) => $this->assertJsonSafe($item)),
            default => throw UnpersistableValueException::create($value),
        };
    }
}
