<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Allocation;

use PhpArchitecture\LazyOperators\Foundation\Extension\Allocation\AllocationFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Allocation\AllocationRemainderTarget;
use PhpArchitecture\LazyOperators\Foundation\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;

class AllocationFunctionSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

    public function supports(Expression $expression): bool
    {
        return $expression instanceof AllocationFunction;
    }

    public function expressionVersion(): string
    {
        return AllocationFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof AllocationFunction);

        return [
            'uid' => AllocationFunction::UID,
            'key' => AllocationFunction::KEY,
            'class' => AllocationFunction::class,
            'version' => AllocationFunction::VERSION,
            'args' => [
                'amount' => $registry->serialize($expression->amount),
                'precision' => $registry->serialize($expression->precision),
                'remainder_target' => ['type' => 'enum', 'case' => $expression->remainderTarget->name],
                'shares' => array_map(
                    static fn (Expression $share) => $registry->serialize($share),
                    $expression->shares,
                ),
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        $shares = array_map(
            fn (mixed $share) => $this->deserializeAs($registry, $share, NumberValue::class),
            $data['args']['shares'],
        );

        return new AllocationFunction(
            $this->deserializeAs($registry, $data['args']['amount'], NumberValue::class),
            $this->deserializeAs($registry, $data['args']['precision'], IntegerValue::class),
            constant(AllocationRemainderTarget::class . '::' . $data['args']['remainder_target']['case']),
            ...$shares,
        );
    }
}
