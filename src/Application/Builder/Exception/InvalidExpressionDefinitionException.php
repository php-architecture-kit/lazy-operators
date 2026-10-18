<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Application\Builder\Exception;

use InvalidArgumentException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Exception\LazyOperatorsException;
use Throwable;

/**
 * A definition passed to ExpressionBuilder does not describe a buildable Expression tree.
 * $path points at the offending node or argument, e.g. "$.arguments.left.arguments.value".
 */
final class InvalidExpressionDefinitionException extends InvalidArgumentException implements LazyOperatorsException
{
    private function __construct(
        public readonly string $path,
        public readonly string $reason,
        ?Throwable $previous = null,
    ) {
        parent::__construct(sprintf('%s: %s', $path, $reason), 0, $previous);
    }

    public static function notAnExpressionDefinition(string $path): self
    {
        return new self($path, 'expected an expression definition object with an "expression" key.');
    }

    public static function unknownExpression(string $path, string $key): self
    {
        return new self($path, sprintf('unknown expression "%s".', $key));
    }

    public static function unavailableExpression(string $path, string $key, string $reason): self
    {
        return new self($path, sprintf('expression "%s" is not available: %s', $key, $reason));
    }

    public static function incompatibleVersion(string $path, string $key, string $given, string $current): self
    {
        return new self($path, sprintf('"%s" definition has version %s, the library provides %s.', $key, $given, $current));
    }

    public static function argumentsNotAnObject(string $path): self
    {
        return new self($path, '"arguments" must be an object of argument name => value.');
    }

    /**
     * @param string[] $known
     */
    public static function unknownArgument(string $path, string $key, string $argument, array $known): self
    {
        return new self($path, sprintf(
            '"%s" has no argument "%s" (expected one of: %s).',
            $key,
            $argument,
            $known === [] ? 'none' : implode(', ', $known),
        ));
    }

    public static function missingArgument(string $path, string $key, string $argument): self
    {
        return new self($path, sprintf('"%s" requires argument "%s".', $key, $argument));
    }

    public static function notAList(string $path): self
    {
        return new self($path, 'expected a list.');
    }

    /**
     * @param string[] $options
     */
    public static function invalidEnumCase(string $path, mixed $given, array $options): self
    {
        return new self($path, sprintf(
            '%s is not one of: %s.',
            is_string($given) ? sprintf('"%s"', $given) : get_debug_type($given),
            implode(', ', $options),
        ));
    }

    public static function unsupportedArgument(string $path, string $reason): self
    {
        return new self($path, $reason);
    }

    public static function argumentBeforeSpreadOmitted(string $path, string $key, string $argument): self
    {
        return new self($path, sprintf('"%s" needs argument "%s" when its spread argument is given.', $key, $argument));
    }

    public static function invalidPortName(string $path): self
    {
        return new self($path, 'a port name must be a non-empty string.');
    }

    public static function typeMismatch(string $path, string $message): self
    {
        return new self($path, $message);
    }

    /**
     * The node's own constructor rejected its arguments, e.g. a date-time literal with a malformed string.
     */
    public static function rejectedByNode(string $path, string $message, Throwable $previous): self
    {
        return new self($path, $message, $previous);
    }
}
