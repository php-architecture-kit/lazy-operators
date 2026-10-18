<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception\EvaluationFailedException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

/**
 * Ends the evaluation with an error when it is reached, for a branch where the inputs have no valid
 * result (e.g. the else of "IF quantity > 0"). A flow either yields a value or fails loudly; this
 * makes the failure an explicit part of the definition instead of an invented placeholder value.
 */
#[Group('Conditional')]
#[Name('Fail')]
#[Formula('f(message) = evaluation fails with message')]
#[Description('Fail stops the evaluation with an EvaluationFailedException carrying the given message, for a branch where the inputs have no valid result.')]
final class FailOperator implements Expression
{
    public const KEY = 'fail';
    public const UID = '03db9ff3-63fe-42c1-907c-71c6383bbc00';
    public const VERSION = '1.0';

    public function __construct(
        public readonly StringValue $message,
    ) {
    }

    public function __invoke(): never
    {
        throw EvaluationFailedException::create(($this->message)());
    }
}
