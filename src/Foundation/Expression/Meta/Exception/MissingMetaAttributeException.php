<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Exception;

use LogicException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Exception\LazyOperatorsException;

final class MissingMetaAttributeException extends LogicException implements LazyOperatorsException
{
    public static function create(string $class, string $attributeClass): self
    {
        return new self(sprintf(
            '"%s" is missing the #[%s] attribute required to describe it as an Expression node.',
            $class,
            $attributeClass,
        ));
    }
}
