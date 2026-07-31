<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Conditional\IfElseOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;

class IfElseOperatorSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

    public function supports(Expression $expression): bool
    {
        return $expression instanceof IfElseOperator;
    }

    public function expressionVersion(): string
    {
        return IfElseOperator::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof IfElseOperator);

        return [
            'uid' => IfElseOperator::UID,
            'key' => IfElseOperator::KEY,
            'class' => IfElseOperator::class,
            'version' => IfElseOperator::VERSION,
            'args' => [
                $registry->serialize($expression->condition),
                $registry->serialize($expression->then),
                $registry->serialize($expression->else),
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new IfElseOperator(
            $this->deserializeAs($registry, $data['args'][0], BooleanValue::class),
            $registry->deserialize($data['args'][1]),
            $registry->deserialize($data['args'][2]),
        );
    }
}
