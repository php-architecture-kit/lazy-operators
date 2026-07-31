<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Static;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Static\ArrayLiteral;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\UnpersistableValueException;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class ArrayLiteralSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof ArrayLiteral;
    }

    public function expressionVersion(): string
    {
        return ArrayLiteral::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof ArrayLiteral);

        $this->assertJsonSafe($expression->value);

        return [
            'uid' => ArrayLiteral::UID,
            'key' => ArrayLiteral::KEY,
            'class' => ArrayLiteral::class,
            'version' => ArrayLiteral::VERSION,
            'args' => [$expression->value],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new ArrayLiteral($data['args'][0]);
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
