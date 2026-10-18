# Expression definition format

This is the specification of the data format that `Application\Builder\ExpressionBuilder` turns into
an expression tree. It is written for anyone who produces definitions outside PHP code: a UI, an API
client, a stored configuration. The [README](README.md#building-expressions-from-data) has the
short introduction; this document is the complete contract.

The format applies to `php-architecture-kit/lazy-operators` 1.7 and later.

## 1. Overview

A definition is a tree of **nodes**. Each node names an expression registered in
`ExpressionRegistry` and gives its arguments. The builder reads the shape of every node (argument
names, which are required, how each is written) from the registry, so the format has no per-node
rules beyond what the registry exposes. Custom expressions follow the same rules once registered.

```php
$builder = new ExpressionBuilder(ExpressionRegistry::default());

$tree = $builder->build(json_decode($json, true, flags: JSON_THROW_ON_ERROR));
$tree->bind('price', new FloatLiteral(9.5));
$result = $tree();
```

`build()` takes a PHP array. When the source is JSON, decode it with `associative: true`: JSON
objects must arrive as arrays, not `stdClass`.

Building and evaluating are separate steps. `build()` either returns a complete tree or throws
`InvalidExpressionDefinitionException`; it never evaluates anything. Evaluation errors come later,
from invoking the tree (section 8).

## 2. Node

A node is an object with these members:

| Member | Required | Value |
|---|---|---|
| `expression` | yes | the expression's key, a string, e.g. `"addition"` (`ExpressionEntry::$key`) |
| `version` | no | a string; when present it must equal the expression's version exactly, e.g. `"1.0"` |
| `arguments` | no | an object mapping argument names to values (section 3); may be left out when every argument is optional or the expression has none |

Other members are ignored.

```json
{
    "expression": "multiplication",
    "version": "1.0",
    "arguments": {
        "left": { "expression": "float_literal", "arguments": { "value": 9.5 } },
        "right": { "expression": "int_literal", "arguments": { "value": 3 } }
    }
}
```

**Version.** An expression's `VERSION` changes when its shape changes. Writing `version` makes a
definition saved against an older shape fail loudly instead of building something different.
It is compared as a string: `"1.0"` matches, the number `1.0` does not.

**Availability.** An expression whose PHP extension is missing (e.g. the `BcMath` nodes without
`ext-bcmath`) stays in the registry with `available: false`; a definition using it is rejected.

## 3. Arguments

Every argument name in `arguments` must be one of the expression's arguments. How a value is
written depends on the argument's kind, which follows from the constructor parameter it maps to:

| Kind | Constructor parameter | Written as | Example |
|---|---|---|---|
| expression | an `Expression` type (`NumberValue`, `DateTimeValue`, ...) | a nested node | `{ "expression": "int_literal", "arguments": { "value": 3 } }` |
| spread | a variadic `...$rest` | a list of nodes, possibly empty | `[ {…}, {…} ]` |
| enum | an enum (also when unioned with a native type, e.g. `RoundingMode\|int`) | the case **name**, case-sensitive | `"HalfEven"` |
| cases | `SwitchCaseOperator::$cases` | a list of objects with `condition` and `value` nodes | `[ { "condition": {…}, "value": {…} } ]` |
| native | only `int`, `float`, `bool`, `string`, `array` (literal nodes, the port name) | the raw JSON value | `3`, `"P7D"`, `["2026-12-24"]` |
| callback | a `Closure` (`CallbackOperator`) | not supported; the definition is rejected | |

### 3.1 Required and optional arguments

- A required argument must be present.
- An optional argument may be left out; the constructor's default applies (e.g. `MonthOverflow::Clip`
  for `datetime_add`). There is no way to write "use the default" explicitly other than omitting it.
- `null` is not a value in this format. Writing `"end": null` is an error (`expected an expression
  definition object`); to leave an optional argument out, omit the member.

### 3.2 Native values

Native values are passed to the constructor as decoded, under PHP's strict types:

- an `int` argument accepts a JSON integer only: `3` is valid, `3.0` decodes to a float and is
  rejected;
- a `float` argument accepts integers and floats;
- `string`, `bool` and `array` must match their JSON type.

A literal may validate its value further when it is built. A temporal literal with a malformed
string (`"2026-10-17 10:00"`, no offset) is rejected at build time with the literal's own message.

### 3.3 Spread arguments

PHP fills a variadic parameter only positionally, so when a spread argument is given, every
argument before it is passed positionally too, and each of those must be present even if it is
optional. When the spread is left out, the others are passed by name as usual.

### 3.4 Enum arguments

The value is the case name as declared in the enum, e.g. `"Day"` for `CalendarUnit::Day` or
`"HalfEven"` for `RoundingMode::HalfEven`. Backed values are not accepted: `1` is not a valid
`mode` for `math_round`, even though PHP's `round()` takes it. The registry lists the valid names
(`EnumArgument::$options`).

### 3.5 Switch cases

Each item of a `cases` list is an object with exactly the fields `CaseOfSwitchCase` declares,
`condition` and `value`, both nodes. Extra members are ignored.

```json
{
    "expression": "switch_case",
    "arguments": {
        "condition": { "expression": "runtime_port", "arguments": { "name": "zone" } },
        "cases": [
            { "condition": { "expression": "string_literal", "arguments": { "value": "EU" } },
              "value": { "expression": "string_literal", "arguments": { "value": "Standard" } } }
        ],
        "default": { "expression": "string_literal", "arguments": { "value": "Unsupported zone" } }
    }
}
```

## 4. Ports

`runtime_port` is the input of a tree. Its only argument, `name`, is a non-empty string.

```json
{ "expression": "runtime_port", "arguments": { "name": "price" } }
```

- Every port node with the same name becomes **one** input: bind it once and every occurrence
  sees the value.
- `ExpressionTree::inputNames()` lists the names in order of first appearance in a depth-first
  walk of the definition, following argument order.
- `ExpressionTree::bind($name, $expression)` binds an `Expression` (usually a literal) to a name;
  an unknown name throws `UnknownExpressionTreeInputException`. A tree can be rebound and invoked
  again any number of times.
- Invoking a tree with an unbound port throws `PortNotBoundException`.

A port is an **untyped** `Expression`: it fits only arguments typed `Expression`. Wrap it in a cast
wherever a typed value is expected (section 5).

## 5. Types

Each expression produces one value type, and each expression argument accepts one. A node fits an
argument when its class implements the argument's type. The check is PHP's own, made when the
node is constructed, so a mismatch is a build-time error.

| Type | PHP value | Subtypes |
|---|---|---|
| `NumberValue` | `int\|float` | `IntegerValue`, `FloatValue` |
| `StringValue` | `string` | |
| `BooleanValue` | `bool` | |
| `ArrayValue` | `array` | |
| `ObjectValue` | `object` | `DateTimeValue` (`DateTimeImmutable`), `IntervalValue` (`DateInterval`), `RangeValue` (`DateTimeRange`) |
| `Expression` | anything | every type above |

Some nodes produce a bare `Expression` because their result has no fixed type:

- `runtime_port`;
- `if_else` and `switch_case`, whose branches may differ in type;
- `array_get`.

To use their result in a typed argument, wrap it in a cast:

| Cast | Produces |
|---|---|
| `cast_integer`, `cast_float`, `cast_string`, `cast_boolean`, `cast_array` | PHP's `(int)`, `(float)`, `(string)`, `(bool)`, `(array)` |
| `cast_datetime` (with a `timezone`) | `DateTimeValue` from a `DateTimeInterface`, an ISO 8601 string with an offset, or a Unix timestamp |
| `cast_interval` | `IntervalValue` from a `DateInterval` or an ISO 8601 interval string |

The PHP casts coerce silently (`(int) "abc"` is `0`); the temporal casts reject values they cannot
read.

## 6. Discovering expressions

`ExpressionRegistry::getAll()` returns an `ExpressionEntry` per expression. A UI or a validator can
generate everything it needs from it.

`ExpressionEntry`:

| Field | Meaning |
|---|---|
| `key` | the value of `expression` |
| `version` | the value `version` must match |
| `uid` | a stable UUID for the expression, independent of its key |
| `type` | the most specific value type it produces, e.g. `IntegerValue` |
| `available`, `unavailableReason` | whether it can be built here, and why not |
| `attributes` | `name`, `group`, `formula`, `description`, for display |
| `arguments` | `ExpressionArgument[]`, in constructor order |

`ExpressionArgument`:

| Field | Meaning |
|---|---|
| `name` | the member name inside `arguments` |
| `type` | the parameter's type with namespaces removed, e.g. `NumberValue`, `RoundingMode\|int`; nullability is not shown, read `optional` instead |
| `itemType` | the element type of a list argument, when declared (`#[ItemTypeOf]`) |
| `spread` | `true` for a variadic argument |
| `optional` | `true` when it may be left out |
| `defaultValue` | the default, for display, or `null` |
| `description` | per-argument help, when declared |

Two subclasses carry extra data:

- `EnumArgument` has `options`, the valid case names, and `enumClass`.
- `CaseArgument` has `fields`, the arguments of each case.

The builder derives an argument's kind from this metadata, in this order:

1. `CallbackArgument` → callback;
2. `EnumArgument` → enum;
3. `CaseArgument` → cases;
4. `spread` → spread;
5. a `type` made only of `int`, `float`, `bool`, `string`, `array`, `null` → native;
6. anything else → expression.

A producer that follows the same order writes definitions the builder accepts.

To accept the definition's types up front, compare the type an argument accepts with every
interface the candidate node's class implements, not only `type`: `int_literal` has
`type: IntegerValue` and also fits a `NumberValue` argument.

## 7. Build errors

Every build error is an `InvalidExpressionDefinitionException`. `$path` locates the problem,
`$reason` says what is wrong, and the message is `"<path>: <reason>"`.

**Paths** start at `$`, the root node:

- `.arguments.<name>` steps into an argument;
- `[<index>]` steps into a list item (spread or cases);
- `.condition` and `.value` step into a switch case.

For example, `$.arguments.cases[0].value.arguments.left`.

| Problem | Path points at | Reason (example) |
|---|---|---|
| not a node object, or no string `expression` | the value | `expected an expression definition object with an "expression" key.` |
| unknown key | the node | `unknown expression "nope".` |
| unavailable expression | the node | `expression "bcmath_add" is not available: Missing ext-bcmath in your PHP server.` |
| version mismatch | the node | `"int_literal" definition has version 0.9, the library provides 1.0.` |
| `arguments` is not an object | `.arguments` | `"arguments" must be an object of argument name => value.` |
| unknown argument | the argument | `"int_literal" has no argument "extra" (expected one of: value).` |
| missing required argument | the argument | `"addition" requires argument "right".` |
| spread or cases not a list | the argument | `expected a list.` |
| unknown enum case | the argument | `"Middle" is not one of: First, Largest, Smallest, Last.` |
| closure argument | the argument | `Closure arguments cannot be described as data.` |
| argument before a given spread omitted | that argument | `"test" needs argument "first" when its spread argument is given.` |
| invalid port name | `.arguments.name` | `a port name must be a non-empty string.` |
| a node or native value of the wrong type | **the node being built** | `Argument #2 ($right) must be of type NumberValue, StringLiteral given` |
| the node's constructor rejects a value | the node being built | the node's own message, e.g. `"2026-10-17 10:00" has no UTC offset or timezone; ...` (the original exception is `getPrevious()`) |

Building stops at the first error.

## 8. Evaluation errors

Invoking a built tree can still fail, because some problems depend on the bound inputs. These
exceptions are not wrapped and have no path. The library's own implement `LazyOperatorsException`;
PHP's own errors pass through unchanged.

| Exception | When |
|---|---|
| `PortNotBoundException` | a port is invoked before anything was bound to it |
| `EvaluationFailedException` | a `fail` node is reached; the message is the one the definition gave |
| `NoMatchedCaseException` | a `switch_case` without a `default` matches no case |
| `Extension\Temporal\Exception\InvalidDateTimeException` | a temporal cast, modifier, timezone, time or holiday list given through a port is invalid |
| `NonExistentLocalTimeException`, `EmptyRangeException`, `OpenRangeException`, `InvalidRangeException` | the temporal cases documented in the README |
| PHP's own errors | e.g. `DivisionByZeroError` from `division` by zero |

## 9. Example

Is going shopping possible? The shop is open Monday to Saturday, 09:00–17:00 in Warsaw; getting
there takes 30 minutes. The current time is the `now` input, bound in any offset.

```json
{
    "expression": "if_else",
    "arguments": {
        "condition": {
            "expression": "and",
            "arguments": {
                "left": {
                    "expression": "less_than_or_equal",
                    "arguments": {
                        "left": { "expression": "datetime_part", "arguments": { "datetime": { "$ref": "arrival" }, "part": "DayOfWeek" } },
                        "right": { "expression": "int_literal", "arguments": { "value": 6 } }
                    }
                },
                "right": {
                    "expression": "time_of_day_between",
                    "arguments": {
                        "datetime": { "$ref": "arrival" },
                        "from": { "expression": "time_literal", "arguments": { "value": "09:00" } },
                        "to": { "expression": "time_literal", "arguments": { "value": "17:00" } }
                    }
                }
            }
        },
        "then": { "expression": "string_literal", "arguments": { "value": "go" } },
        "else": { "expression": "fail", "arguments": { "message": { "expression": "string_literal", "arguments": { "value": "the shop is open 09:00-17:00" } } } }
    }
}
```

`{ "$ref": "arrival" }` is **not** part of the format; it stands for this node, written out in full
at both places:

```json
{
    "expression": "datetime_add",
    "arguments": {
        "datetime": {
            "expression": "cast_datetime",
            "arguments": {
                "value": { "expression": "runtime_port", "arguments": { "name": "now" } },
                "timezone": { "expression": "string_literal", "arguments": { "value": "Europe/Warsaw" } }
            }
        },
        "interval": { "expression": "interval_literal", "arguments": { "value": "PT30M" } }
    }
}
```

```php
$tree = $builder->build($definition);
$tree->bind('now', new StringLiteral('2026-10-17T08:00:00Z'));
$tree(); // "go" — Saturday 10:00 in Warsaw
```

## 10. Not covered

- **Shared subtrees.** The format has no references. A subtree used twice is written twice and
  built as two nodes; only ports are shared by name.
- **Closures.** `CallbackOperator` cannot be described as data.
- **The reverse direction.** There is no serializer from a built tree back to a definition; keep the
  definition you built from.
