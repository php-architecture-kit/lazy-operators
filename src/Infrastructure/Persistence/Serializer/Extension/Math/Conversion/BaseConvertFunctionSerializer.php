<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\BaseConvertFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class BaseConvertFunctionSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof BaseConvertFunction;
    }

    public function expressionVersion(): string
    {
        return BaseConvertFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof BaseConvertFunction);

        return [
            'uid' => BaseConvertFunction::UID,
            'key' => BaseConvertFunction::KEY,
            'class' => BaseConvertFunction::class,
            'version' => BaseConvertFunction::VERSION,
            'args' => [
                'value' => $registry->serialize($expression->value),
                'fromBase' => $registry->serialize($expression->fromBase),
                'toBase' => $registry->serialize($expression->toBase),
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new BaseConvertFunction(
            $registry->deserialize($data['args']['value']),
            $registry->deserialize($data['args']['fromBase']),
            $registry->deserialize($data['args']['toBase']),
        );
    }
}
