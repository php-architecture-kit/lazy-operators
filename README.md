# Lazy Operators

[![Coverage](https://img.shields.io/endpoint?url=https://raw.githubusercontent.com/php-architecture-kit/lazy-operators/master/.github/badges/coverage.json)](https://github.com/php-architecture-kit/lazy-operators/actions/workflows/ci.yml)

Lazy, composable expression operators for PHP: arithmetic, comparison, logical, and conditional
building blocks that you assemble into a tree and evaluate only when you call it.

## What it is

Every value in this library is an `Expression`. An `Expression` holds its operands. It does not
compute anything until you call it. `Arithmetic::of(2)->multiply(3)->add(4)->build()` builds a
three-node tree and returns immediately. The addition, the multiplication, and the two literals
stay untouched until you invoke the result with `$expr()`.

This buys you one thing in particular: you can build a piece of logic once and reuse it from
several starting points without re-running it (see the forking example below).

## Requirements

- PHP `^8.4`
- `ext-bcmath`, optional, for `Foundation\Expression\Extension\BcMath`
- `ext-math`, optional, for additional native math functions used by some `Extension\Math` methods

## Installation

```bash
composer require php-architecture-kit/lazy-operators
```

## Quick start

```php
use PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic\Arithmetic;

$expr = Arithmetic::of(2)
    ->multiply(3)
    ->add(4)
    ->build();

$expr(); // 10 -- (2 * 3) + 4, computed only on this line
```

Reuse a partial chain from more than one point without mutating it:

```php
$base = Arithmetic::of(2)->add(3);

$timesFour = $base->multiply(4)->build();
$timesTen  = $base->multiply(10)->build();

$timesFour(); // 20 -- (2 + 3) * 4
$timesTen();  // 50 -- (2 + 3) * 10, $base itself was never changed
```

## Core concepts

The root contract is `Foundation\Expression\Expression`, from the `Expression` subdomain of `Foundation`:

```php
interface Expression
{
    public function __invoke(): mixed;
}
```

`Foundation\Expression\Type` narrows this contract for each kind of value an operator can produce:
`NumberValue` (`int|float`), `IntegerValue` and `FloatValue` (each a narrower `NumberValue`),
`StringValue`, `BooleanValue`, `ArrayValue`, and `ObjectValue`. An operator that needs a number
type-hints `NumberValue` on its operand. This lets one operator's output plug directly
into the next operator's constructor.

You rarely construct these node classes directly. Every operator accepts either a raw scalar
(`2`, `'x'`, `true`) or an `Expression`, and wraps a raw scalar into the matching literal node for
you. This is why `Arithmetic::of(2)->add(3)` and `Arithmetic::of(2)->add(SomeExpression)` both
work.

Each facade also accepts an optional `ExpressionTreeConfig` with a decorator. When set, every node the
facade builds gets wrapped in your decorator. This is a plain way to add logging, caching, or
tracing around every operation, without changing any operator code.

You supply a factory, called once per node with the node it wraps:

```php
use PhpArchitecture\LazyOperators\Foundation\Expression\Decorator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;

$config = ExpressionTreeConfig::decoratedBy(
    static fn (Expression $node): Decorator => new TracingDecorator($node, $logger),
);

Arithmetic::of(2, $config)->multiply(3)->add(4)->build();
```

Because you construct the decorator yourself, it can take whatever else it needs — a logger, a cache
pool, a channel name — alongside the node.

A factory is the only way to configure decoration. Passing a `Decorator` instance
(`new ExpressionTreeConfig($decorator)`) was deprecated in 1.5.1 and **removed in 1.6.0**, along with
the `ExpressionTreeConfig::$decorator` property; `$decoratorFactory` holds the factory, and `null`
there means no decoration.

```php
// before 1.6.0 -- the IntLiteral(0) was a throwaway, and $logger never arrived
new ExpressionTreeConfig(new TracingDecorator(new IntLiteral(0), $logger));

// 1.6.0 onwards
ExpressionTreeConfig::decoratedBy(
    static fn (Expression $node): Decorator => new TracingDecorator($node, $logger),
);
```

## Available operators

The core namespaces have no dependency on any PHP extension.

| Facade | Namespace | Operations |
|---|---|---|
| `Arithmetic` | `Foundation\Expression\Arithmetic` | `add`, `subtract`, `multiply`, `divide`, `modulo`, `power` |
| `Logical` | `Foundation\Expression\Logical` | `and`, `or`, `xor`, `not` |
| `Comparison` | `Foundation\Expression\Comparison` | `equal`, `notEqual`, `identical`, `notIdentical`, `greaterThan`, `greaterThanOrEqual`, `lessThan`, `lessThanOrEqual` |
| `Comparator` | `Foundation\Expression\Comparator` | `spaceship` (PHP's `<=>`) |
| `Conditional` | `Foundation\Expression\Conditional` | `if(...)->then(...)->else(...)`, `switch(...)->case(...)->default(...)`, `fail($message)` |
| `Custom` | `Foundation\Expression\Custom` | `callback(Closure $fn, ...$args)`, for logic none of the above cover |

Each facade builds with a fluent chain and returns the tree with `->build()`:

```php
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\Comparison;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Conditional;

$label = Conditional::if(Comparison::of($x)->greaterThan(10)->build())
    ->then('big')
    ->else('small')
    ->build();

$label(); // 'big' or 'small', depending on $x
```

A tree is a single flow that always ends in a result, so an `if` needs both branches. When the inputs
leave a branch with no valid result, end it with `fail()` instead of an invented value: evaluating that
branch throws `EvaluationFailedException` with your message.

```php
$unitPrice = Conditional::if(Comparison::of($quantity)->greaterThan(0)->build())
    ->then(Arithmetic::of($total)->divide($quantity)->build())
    ->else(Conditional::fail('quantity must be positive'))
    ->build();
```

## Building expressions from data

When the tree comes from outside your code (a UI, an API request, a stored configuration), describe
it as plain data and let `Application\Builder\ExpressionBuilder` build it. The builder reads the
shape of every node from `ExpressionRegistry`, so the same contract covers every operator,
including your own once you register them.

Each node is an object with the expression key, an optional version, and its arguments:

```json
{
    "expression": "multiplication",
    "version": "1.0",
    "arguments": {
        "left": { "expression": "cast_float", "arguments": { "value": { "expression": "runtime_port", "arguments": { "name": "price" } } } },
        "right": { "expression": "int_literal", "arguments": { "value": 3 } }
    }
}
```

How an argument is written depends on what the constructor takes:

| Argument | Written as |
|---|---|
| an `Expression` (any value type) | a nested node |
| a spread (`...$rest`) | a list of nodes |
| an enum | the case name, e.g. `"HalfEven"` |
| `SwitchCaseOperator::$cases` | a list of `{ "condition": node, "value": node }` |
| a native value (literal nodes, the port name) | the raw value, e.g. `3` or `"price"` |
| a `Closure` (`CallbackOperator`) | not supported: a closure cannot be described as data |

Optional arguments may be left out. `version` is optional; when given, it must match the node's
`VERSION`, so a definition saved against an older shape fails loudly instead of building something
different.

`build()` returns an `ExpressionTree`. Ports with the same name share one input, which you bind before
invoking the tree. A port is an untyped `Expression`, so wrap it in a cast (`cast_float`,
`cast_integer`, ...) where an operator expects a typed value.

```php
use PhpArchitecture\LazyOperators\Application\Builder\ExpressionBuilder;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\FloatLiteral;
use PhpArchitecture\LazyOperators\Infrastructure\Registry\ExpressionRegistry;

$builder = new ExpressionBuilder(ExpressionRegistry::default());

$tree = $builder->build(json_decode($json, true, flags: JSON_THROW_ON_ERROR));
$tree->inputNames();                         // ['price']
$tree->bind('price', new FloatLiteral(9.5));
$tree();                                     // 28.5
```

An invalid definition throws `InvalidExpressionDefinitionException`. Its `$path` points at the
offending node or argument (e.g. `$.arguments.left`) and its message says what is wrong, including
PHP's own type error when a node is plugged into an argument of the wrong type.

The complete format, with every argument kind, type rule and error, is specified in
[BUILDER.md](BUILDER.md).

## Extensions

`Foundation\Expression\Extension` holds anything beyond the core language: a full wrapper around a native
PHP function group, code that depends on an optional extension, or a ready-made domain recipe
built from core operators.

| Extension | Namespace | Covers |
|---|---|---|
| `Math` | `Extension\Math` | Rounding, trigonometry, exponential/logarithmic functions, base conversion, random numbers, and value classification — one method per native PHP math function |
| `List` | `Extension\List` | Reducing an unbounded list of values to a single scalar: `sum`, `product` — computed via bcmath when available, native arithmetic otherwise |
| `Array` | `Extension\Array` | Genuinely array-shaped operations: `get` (dot-path lookup) |
| `BcMath` | `Extension\BcMath` | Arbitrary-precision arithmetic (`add`, `sub`, `mul`, `div`, `comp`), requires `ext-bcmath` |
| `Allocation` | `Extension\Allocation` | Proportional split of an amount across shares, remainder folded into one share |
| `Temporal` | `Extension\Temporal` | Timezone-aware dates and times over `DateTimeImmutable`: calendar arithmetic, wall-clock reads, validity ranges, business days — see [Dates and time](#dates-and-time) |

`BcMath` avoids the classic floating-point trap and computes exact decimal results:

```php
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Allocation\Allocation;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\BcMath\BcMath;

// Native PHP: 0.1 + 0.2 === 0.30000000000000004
$amount = BcMath::add('0.1', '0.2', 1);

$result = Allocation::allocate($amount, [1, 1], 2)();
// [0.15, 0.15]
```

`Allocation` picks its own strategy at run time: it uses `bcmath` when it's loaded, and falls back
to native floats otherwise. The node it builds is `AllocationFunction`, with a public
`useBcMathIfAvailable` property (default `true`) that you can set to `false` to force the
native-float path even when `bcmath` is available:

```php
$allocation = Allocation::allocate(100, [1, 1, 1], 2);
$allocation->useBcMathIfAvailable = false; // force native floats
$allocation();
```

## Dates and time

`Extension\Temporal` works on PHP's own `DateTimeImmutable` and `DateInterval`; it adds no calendar
of its own. Three value types mark what flows between its nodes: `DateTimeValue`
(`DateTimeImmutable`), `IntervalValue` (`DateInterval`) and `RangeValue` (a half-open
`DateTimeRange`, the one class the extension adds).

**The timezone is given once.** `cast_datetime(value, timezone)` (or `Temporal::datetime()`) puts a
point in time into an IANA timezone, and every later calendar or wall-clock operation runs in it.
"What hour is it", "which day is it" and "+1 day" only have an answer in a timezone, and taking it
from wherever the value happened to come from (a UTC timestamp, the server default) gives wrong
answers silently. For the same reason, strings without an offset (`"2026-10-17 10:00"`) and
relative strings (`"tomorrow"`) are rejected: the current time comes in through a port.

```php
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparison\Comparison;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\DatePart;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Temporal;
use PhpArchitecture\LazyOperators\Foundation\Expression\Logical\Logical;

// The shop is open Monday to Saturday, 09:00-17:00 in Warsaw; getting there takes 30 minutes.
$arrival = Temporal::add(Temporal::datetime($now, 'Europe/Warsaw'), 'PT30M');

$open = Logical::of(Comparison::of(Temporal::part($arrival, DatePart::DayOfWeek))->lessThanOrEqual(6)->build())
    ->and(Temporal::timeOfDayBetween($arrival, '09:00', '17:00'))
    ->build();

$open(); // false on Sunday, false at 16:45 (arriving 17:15), regardless of the zone $now was given in
```

| Node | Does |
|---|---|
| `cast_datetime`, `datetime_literal` | read a `DateTimeInterface`, an ISO 8601 string with an offset, or a Unix timestamp, into a timezone |
| `cast_interval`, `interval_literal` | read an ISO 8601 interval: `"P7D"`, `"P1M"`, `"PT30M"` |
| `time_literal` | a wall-clock time, `"09:00"` |
| `datetime_modify` | PHP's relative formats: `"+7 days"`, `"+7 weekdays"`, `"midnight"`, `"last day of next month"` |
| `datetime_add`, `datetime_sub` | add or subtract an interval; calendar parts follow the calendar, time parts elapsed time |
| `datetime_at_time` | the same day at another wall-clock time |
| `start_of` | the first instant of the `Day`, `Week` (Monday), `Month` or `Year` |
| `datetime_part` | one wall-clock field as an integer: `Year` ... `Second`, `DayOfWeek`, `DayOfYear`, `IsoWeek`, `SecondOfDay` |
| `datetime_format` | a PHP date format, as a string |
| `datetime_timestamp` | the Unix timestamp, for arithmetic and comparison operators |
| `datetime_diff`, `interval_seconds` | the calendar interval, or the exact seconds, between two date-times |
| `time_of_day_between` | `from <= time < to`; a window ending before it starts wraps past midnight (`"22:00"`-`"06:00"`) |
| `datetime_range`, `range_contains`, `range_overlaps`, `range_intersection`, `range_start`, `range_end`, `range_any_contains` | validity periods, see below |
| `business_day_is`, `business_days_add`, `business_days_between` | business days, see below |

Date-times compare with the existing comparison operators, which compare instants:
`12:00 UTC == 14:00 Europe/Warsaw`. (`identical` compares object identity, so do not use it on
date-times.) Intervals cannot be compared; compare `interval_seconds` instead.

### Calendar edge cases, decided explicitly

| Case | Behaviour |
|---|---|
| A month added to 31 January | `datetime_add` clips to the last day of the month (28 February) by default; `MonthOverflow::Overflow` keeps PHP's native 3 March. `datetime_modify("+1 month")` is always PHP's native result. |
| `P1D` vs `PT24H` across a clock change | `P1D` is the same wall-clock time the next day, `PT24H` exactly 24 hours later |
| A wall-clock time the clocks skip (02:30 when DST starts) | `datetime_at_time` moves it forward (`DstGap::Forward`, PHP's choice) or fails (`DstGap::Reject`) |
| A wall-clock time the clocks show twice (02:30 when DST ends) | `datetime_at_time` takes the second occurrence (`DstFold::Later`, PHP's choice) or the first (`DstFold::Earlier`) |
| A day whose midnight is skipped or repeated | `start_of` returns the first instant the day actually has |
| `SecondOfDay` on a 23- or 25-hour day | read from the wall clock: 12:00 is always 43200 |

### Validity

A `DateTimeRange` is half-open, `[start, end)`: it contains its start and not its end, so
back-to-back periods never overlap. Leave the end out for a period that lasts indefinitely. Build
date boundaries with `start_of(..., Day)` rather than literals with a fixed offset: midnight in
Warsaw is `+01:00` in winter and `+02:00` in summer.

Intersecting disjoint ranges and asking for the end of an open range fail (`EmptyRangeException`,
`OpenRangeException`) rather than inventing a value; check with `range_overlaps` first when that can
happen.

### Business days

Holidays are a list of `"YYYY-MM-DD"` dates and the weekend a list of ISO days (1 = Monday),
Saturday and Sunday by default. Both are data, typically bound to a port and wrapped in
`cast_array`; movable holidays are computed by the caller.

| Case | Behaviour |
|---|---|
| The starting day | never counts: from Saturday, 1 business day later is Monday |
| Zero days | the date-time unchanged (PHP's `"+0 weekdays"` moves a Saturday to Monday) |
| Negative days | moves back: Monday - 1 is Friday |
| The time of day | kept |
| `business_days_between(from, to)` | business days after `from` up to and including `to`, negative when `to` is earlier, so it undoes `business_days_add` |

## Writing your own extension

The library has a consistent pattern for adding a new operator, whether it lives in
`Foundation\Expression\Extension` or your own code. Every concrete node class:

1. Declares its own `KEY` (a short string label), `UID` (a UUIDv4 string), and `VERSION` constant.
   `KEY` and `UID` must be unique across the whole set of registered nodes.
2. Implements exactly one typed contract from `Foundation\Expression\Type` — `NumberValue`, `StringValue`,
   `BooleanValue`, `ArrayValue`, `ObjectValue`, or a package-specific one such as
   `Extension\BcMath\PrecisionNumberValue`.
3. Exposes a static `formula(): string` method that documents the computation in plain text, for
   example `AdditionOperator::formula()` returns `'f(left, right) = left + right'`.
4. If it wraps a native PHP function, it adds `use GuardsNativeFunction;`. Its constructor calls
   `self::guardAvailable('function_name')`. This throws a clear exception when the underlying
   function is not loaded, instead of a generic PHP error later.

`Extension\Allocation` and `Extension\BcMath` follow this pattern end to end and are good
references to copy from.

## Testing and quality tooling

```bash
composer test           # PHPUnit
composer test:coverage  # PHPUnit with Cobertura coverage output
composer code:analyse   # PHPStan, level 8
composer code:fix:dry   # PHP-CS-Fixer, check only
composer code:fix       # PHP-CS-Fixer, apply fixes
```

Tests live under `tests/Unit` (one file per class under `src/Foundation`, mirroring its `Expression`/
`Registry` subdomains), `tests/Functional`
(cross-cutting scenarios, such as BcMath feeding Allocation), and `tests/Support` (test doubles
for `Expression`).

## Contributing

Run `composer test`, `composer code:analyse`, and `composer code:fix:dry` before opening a pull
request. All three run in CI on every push.

## License

MIT. See [LICENSE](LICENSE).
