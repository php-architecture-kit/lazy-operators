<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\BcMath\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcAddFunction;
use PhpArchitecture\LazyOperators\Foundation\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;

class BcAddFunctionSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

    public function supports(Expression $expression): bool
    {
        return $expression instanceof BcAddFunction;
    }

    public function expressionVersion(): string
    {
        return BcAddFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof BcAddFunction);

        return [
            'uid' => BcAddFunction::UID,
            'key' => BcAddFunction::KEY,
            'class' => BcAddFunction::class,
            'version' => BcAddFunction::VERSION,
            'args' => [
                $registry->serialize($expression->left),
                $registry->serialize($expression->right),
                $expression->scale === null ? null : $registry->serialize($expression->scale),
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new BcAddFunction(
            $this->deserializeAs($registry, $data['args'][0], NumberValue::class),
            $this->deserializeAs($registry, $data['args'][1], NumberValue::class),
            $data['args'][2] === null ? null : $this->deserializeAs($registry, $data['args'][2], IntegerValue::class),
        );
    }
}
