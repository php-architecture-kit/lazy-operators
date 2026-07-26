<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\Atan2Function;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class Atan2FunctionSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof Atan2Function;
    }

    public function expressionVersion(): string
    {
        return Atan2Function::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof Atan2Function);

        return [
            'uid' => Atan2Function::UID,
            'key' => Atan2Function::KEY,
            'class' => Atan2Function::class,
            'version' => Atan2Function::VERSION,
            'args' => [
                'y' => $registry->serialize($expression->y),
                'x' => $registry->serialize($expression->x),
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new Atan2Function(
            $registry->deserialize($data['args']['y']),
            $registry->deserialize($data['args']['x']),
        );
    }
}
