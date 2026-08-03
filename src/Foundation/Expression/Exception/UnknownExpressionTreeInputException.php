<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Exception;

use LogicException;

final class UnknownExpressionTreeInputException extends LogicException implements LazyOperatorsException
{
    public static function create(string $name): self
    {
        return new self(sprintf(
            'ExpressionTree has no input named "%s".',
            $name,
        ));
    }
}
