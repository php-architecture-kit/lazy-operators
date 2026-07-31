<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

trait DeserializesNarrowly
{
    /**
     * Deserializes a stored arg and re-asserts it as $type: ExpressionSerializerRegistry::deserialize()
     * only guarantees the generic Expression contract, but the reconstructed node's own constructor is
     * narrowed (e.g. NumberValue/BooleanValue), matching how the tree was built in the first place.
     *
     * @template T of Expression
     *
     * @param class-string<T> $type
     *
     * @return T
     */
    private function deserializeAs(ExpressionSerializerRegistry $registry, mixed $data, string $type): Expression
    {
        $expression = $registry->deserialize($data);
        assert($expression instanceof $type);

        return $expression;
    }
}
