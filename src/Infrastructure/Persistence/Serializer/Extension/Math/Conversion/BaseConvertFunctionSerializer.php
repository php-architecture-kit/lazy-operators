<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Conversion;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\BaseConvertFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Type\StringValue;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;
class BaseConvertFunctionSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

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
            $this->deserializeAs($registry, $data['args']['value'], StringValue::class),
            $this->deserializeAs($registry, $data['args']['fromBase'], NumberValue::class),
            $this->deserializeAs($registry, $data['args']['toBase'], NumberValue::class),
        );
    }
}
