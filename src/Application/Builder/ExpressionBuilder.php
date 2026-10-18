<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Application\Builder;

use PhpArchitecture\LazyOperators\Application\Builder\Exception\InvalidExpressionDefinitionException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\CaseOfSwitchCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Exception\LazyOperatorsException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTree;
use PhpArchitecture\LazyOperators\Foundation\Expression\Port;
use PhpArchitecture\LazyOperators\Foundation\Expression\Ports;
use PhpArchitecture\LazyOperators\Foundation\Registry\Entry\Argument\CallbackArgument;
use PhpArchitecture\LazyOperators\Foundation\Registry\Entry\Argument\CaseArgument;
use PhpArchitecture\LazyOperators\Foundation\Registry\Entry\Argument\EnumArgument;
use PhpArchitecture\LazyOperators\Foundation\Registry\Entry\ExpressionArgument;
use PhpArchitecture\LazyOperators\Foundation\Registry\Entry\ExpressionEntry;
use PhpArchitecture\LazyOperators\Foundation\Registry\ExpressionRegistryInterface;
use TypeError;

/**
 * Builds an Expression tree from a plain-data definition (e.g. decoded JSON sent by a UI).
 *
 * A definition node is an object:
 *
 *     {
 *         "expression": "addition",          // ExpressionEntry::$key
 *         "version": "1.0",                  // optional, checked against ExpressionEntry::$version
 *         "arguments": { "left": …, "right": … }
 *     }
 *
 * Each argument value is read according to the registry's metadata for that argument:
 *
 * - an Expression-typed argument takes a nested definition node;
 * - a spread argument takes a list of definition nodes;
 * - an enum argument takes the case name, e.g. "HalfUp";
 * - a CaseOfSwitchCase list takes a list of { "condition": node, "value": node };
 * - a native argument (literals, the Port name) takes the raw value, e.g. 5 or "price";
 * - a Closure argument cannot be described as data and is rejected.
 *
 * Optional arguments may be left out. Port nodes with the same name share one Port, and the
 * returned ExpressionTree exposes them for binding before it is invoked.
 */
final class ExpressionBuilder
{
    private const NATIVE_TYPES = ['int', 'float', 'bool', 'string', 'array', 'null'];

    /**
     * @var array<string, ExpressionEntry>
     */
    private array $entries = [];

    public function __construct(ExpressionRegistryInterface $registry)
    {
        foreach ($registry->getAll() as $entry) {
            $this->entries[$entry->key] = $entry;
        }
    }

    /**
     * @param array<mixed> $definition
     *
     * @throws InvalidExpressionDefinitionException
     */
    public function build(array $definition): ExpressionTree
    {
        $ports = new Ports();

        return new ExpressionTree($this->buildNode($definition, '$', $ports), $ports);
    }

    private function buildNode(mixed $definition, string $path, Ports $ports): Expression
    {
        if (!is_array($definition) || !is_string($definition['expression'] ?? null)) {
            throw InvalidExpressionDefinitionException::notAnExpressionDefinition($path);
        }

        $key = $definition['expression'];
        $entry = $this->entries[$key] ?? throw InvalidExpressionDefinitionException::unknownExpression($path, $key);

        if (!$entry->available) {
            throw InvalidExpressionDefinitionException::unavailableExpression($path, $key, (string) $entry->unavailableReason);
        }

        $version = $definition['version'] ?? null;

        if ($version !== null && $version !== $entry->version) {
            throw InvalidExpressionDefinitionException::incompatibleVersion($path, $key, (string) $version, $entry->version);
        }

        $arguments = $definition['arguments'] ?? [];

        if (!is_array($arguments)) {
            throw InvalidExpressionDefinitionException::argumentsNotAnObject($path . '.arguments');
        }

        $this->assertKnownArguments($entry, $arguments, $path);

        if ($entry->fqcn === Port::class) {
            return $this->buildPort($arguments, $path, $ports);
        }

        $values = [];

        foreach ($entry->arguments as $argument) {
            $argumentPath = $path . '.arguments.' . $argument->name;

            if (!array_key_exists($argument->name, $arguments)) {
                if (!$argument->optional) {
                    throw InvalidExpressionDefinitionException::missingArgument($argumentPath, $key, $argument->name);
                }

                continue;
            }

            $values[$argument->name] = $this->buildArgument($argument, $arguments[$argument->name], $argumentPath, $ports);
        }

        return $this->instantiate($entry, $values, $path);
    }

    private function buildArgument(ExpressionArgument $argument, mixed $value, string $path, Ports $ports): mixed
    {
        return match (true) {
            $argument instanceof CallbackArgument => throw InvalidExpressionDefinitionException::unsupportedArgument(
                $path,
                'Closure arguments cannot be described as data.',
            ),
            $argument instanceof EnumArgument => $this->buildEnumCase($argument, $value, $path),
            $argument instanceof CaseArgument => $this->buildCases($argument, $value, $path, $ports),
            $argument->spread => $this->buildNodes($value, $path, $ports),
            $this->isNativeType($argument->type) => $value,
            default => $this->buildNode($value, $path, $ports),
        };
    }

    /**
     * @return Expression[]
     */
    private function buildNodes(mixed $value, string $path, Ports $ports): array
    {
        $nodes = [];

        foreach ($this->assertList($value, $path) as $index => $item) {
            $nodes[] = $this->buildNode($item, sprintf('%s[%d]', $path, $index), $ports);
        }

        return $nodes;
    }

    private function buildEnumCase(EnumArgument $argument, mixed $value, string $path): mixed
    {
        foreach ($argument->enumClass::cases() as $case) {
            if ($case->name === $value) {
                return $case;
            }
        }

        throw InvalidExpressionDefinitionException::invalidEnumCase($path, $value, $argument->options);
    }

    /**
     * @return CaseOfSwitchCase[]
     */
    private function buildCases(CaseArgument $argument, mixed $value, string $path, Ports $ports): array
    {
        $cases = [];

        foreach ($this->assertList($value, $path) as $index => $case) {
            $casePath = sprintf('%s[%d]', $path, $index);

            if (!is_array($case)) {
                throw InvalidExpressionDefinitionException::notAnExpressionDefinition($casePath);
            }

            $fields = [];

            foreach ($argument->fields as $field) {
                if (!array_key_exists($field->name, $case)) {
                    throw InvalidExpressionDefinitionException::missingArgument($casePath, 'case', $field->name);
                }

                $fields[$field->name] = $this->buildNode($case[$field->name], $casePath . '.' . $field->name, $ports);
            }

            $cases[] = new CaseOfSwitchCase(...$fields);
        }

        return $cases;
    }

    /**
     * @param array<mixed> $arguments
     */
    private function buildPort(array $arguments, string $path, Ports $ports): Port
    {
        $name = $arguments['name'] ?? null;

        if (!is_string($name) || $name === '') {
            throw InvalidExpressionDefinitionException::invalidPortName($path . '.arguments.name');
        }

        return $ports->named($name);
    }

    /**
     * Arguments are passed by name, except when a spread argument is given: PHP only fills a
     * variadic parameter positionally, so every argument before it is passed positionally too.
     *
     * @param array<string, mixed> $values
     */
    private function instantiate(ExpressionEntry $entry, array $values, string $path): Expression
    {
        /** @var class-string<Expression> $class */
        $class = $entry->fqcn;
        $spread = $entry->arguments === [] ? null : $entry->arguments[array_key_last($entry->arguments)];

        try {
            if ($spread === null || !$spread->spread || !array_key_exists($spread->name, $values)) {
                return new $class(...$values);
            }

            $positional = [];

            foreach ($entry->arguments as $argument) {
                if ($argument === $spread) {
                    break;
                }

                if (!array_key_exists($argument->name, $values)) {
                    throw InvalidExpressionDefinitionException::argumentBeforeSpreadOmitted(
                        $path . '.arguments.' . $argument->name,
                        $entry->key,
                        $argument->name,
                    );
                }

                $positional[] = $values[$argument->name];
            }

            /** @var Expression[] $spreadValues */
            $spreadValues = $values[$spread->name];

            return new $class(...$positional, ...$spreadValues);
        } catch (TypeError $error) {
            throw InvalidExpressionDefinitionException::typeMismatch($path, $this->describeTypeError($error));
        } catch (InvalidExpressionDefinitionException $exception) {
            throw $exception;
        } catch (LazyOperatorsException $exception) {
            throw InvalidExpressionDefinitionException::rejectedByNode($path, $exception->getMessage(), $exception);
        }
    }

    /**
     * @param array<mixed> $arguments
     */
    private function assertKnownArguments(ExpressionEntry $entry, array $arguments, string $path): void
    {
        $known = array_map(static fn (ExpressionArgument $argument): string => $argument->name, $entry->arguments);

        foreach (array_keys($arguments) as $name) {
            if (!in_array($name, $known, true)) {
                throw InvalidExpressionDefinitionException::unknownArgument(
                    $path . '.arguments.' . $name,
                    $entry->key,
                    (string) $name,
                    $known,
                );
            }
        }
    }

    /**
     * @return list<mixed>
     */
    private function assertList(mixed $value, string $path): array
    {
        if (!is_array($value) || !array_is_list($value)) {
            throw InvalidExpressionDefinitionException::notAList($path);
        }

        return $value;
    }

    private function isNativeType(string $type): bool
    {
        $tokens = explode('|', ltrim($type, '?'));

        return array_diff($tokens, self::NATIVE_TYPES) === [];
    }

    /**
     * Keeps PHP's own wording ("Argument #1 ($left) must be of type NumberValue, StringLiteral given")
     * but drops namespaces and the "called in <file>" location, which mean nothing to a data author.
     */
    private function describeTypeError(TypeError $error): string
    {
        $message = (string) preg_replace('/, called in .*$/s', '', $error->getMessage());
        $message = (string) preg_replace('/^[\w\\\\]+::__construct\(\): /', '', $message);

        return (string) preg_replace('/(?:\w+\\\\)+(\w+)/', '$1', $message);
    }
}
