# Lazy Operators

[![Coverage](https://img.shields.io/endpoint?url=https://raw.githubusercontent.com/php-architecture-kit/lazy-operators/master/.github/badges/coverage.json)](https://github.com/php-architecture-kit/lazy-operators/actions/workflows/ci.yml)

Lazy, composable expression operators for PHP: arithmetic, comparison, logical, and conditional
building blocks that you assemble into a tree and evaluate only when you call it.

## What it is

Every value in this library is an `Expression`. An `Expression` holds its operands. It does not
compute anything until you call it. `Arithmetic::of(2)->multiply(3)->add(4)->build()` builds a
three-node tree and returns immediately. The addition, the multiplication, and the two literals
stay untouched until you invoke the result with `$expr()`.

This buys two things. First, you can build a piece of logic once and reuse it from several
starting points without re-running it (see the forking example below). Second, an expression tree
is a plain object graph. You can serialize it, store it, and rebuild the exact same computation
later (see "Persisting expressions").

## Requirements

- PHP `^8.4`
- `ext-bcmath`, optional, for `Foundation\Extension\BcMath`
- `ext-math`, optional, for additional native math functions used by some `Extension\Math` methods

## Installation

```bash
composer require php-architecture-kit/lazy-operators
```

## Quick start

```php
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\Arithmetic;

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

The root contract is `Foundation\Expression`:

```php
interface Expression
{
    public function __invoke(): mixed;
}
```

`Foundation\Type` narrows this contract for each kind of value an operator can produce:
`NumberValue` (`int|float`), `IntegerValue` and `FloatValue` (each a narrower `NumberValue`),
`StringValue`, `BooleanValue`, `ArrayValue`, and `ObjectValue`. An operator that needs a number
type-hints `NumberValue` on its operand. This lets one operator's output plug directly
into the next operator's constructor.

You rarely construct these node classes directly. Every operator accepts either a raw scalar
(`2`, `'x'`, `true`) or an `Expression`, and wraps a raw scalar into the matching literal node for
you. This is why `Arithmetic::of(2)->add(3)` and `Arithmetic::of(2)->add(SomeExpression)` both
work.

Each facade also accepts an optional `PipelineConfig` with a decorator. When set, every node the
facade builds gets wrapped in your decorator. This is a plain way to add logging, caching, or
tracing around every operation, without changing any operator code.

## Available operators

The core namespaces have no dependency on any PHP extension.

| Facade | Namespace | Operations |
|---|---|---|
| `Arithmetic` | `Foundation\Arithmetic` | `add`, `subtract`, `multiply`, `divide`, `modulo`, `power` |
| `Logical` | `Foundation\Logical` | `and`, `or`, `xor`, `not` |
| `Comparison` | `Foundation\Comparison` | `equal`, `notEqual`, `identical`, `notIdentical`, `greaterThan`, `greaterThanOrEqual`, `lessThan`, `lessThanOrEqual` |
| `Comparator` | `Foundation\Comparator` | `spaceship` (PHP's `<=>`) |
| `Conditional` | `Foundation\Conditional` | `if(...)->then(...)->else(...)`, `switch(...)->case(...)->default(...)` |
| `Custom` | `Foundation\Custom` | `callback(Closure $fn, ...$args)`, for logic none of the above cover |

Each facade builds with a fluent chain and returns the tree with `->build()`:

```php
use PhpArchitecture\LazyOperators\Foundation\Comparison\Comparison;
use PhpArchitecture\LazyOperators\Foundation\Conditional\Conditional;

$label = Conditional::if(Comparison::of($x)->greaterThan(10)->build())
    ->then('big')
    ->else('small')
    ->build();

$label(); // 'big' or 'small', depending on $x
```

## Extensions

`Foundation\Extension` holds anything beyond the core language: a full wrapper around a native
PHP function group, code that depends on an optional extension, or a ready-made domain recipe
built from core operators.

| Extension | Namespace | Covers |
|---|---|---|
| `Math` | `Extension\Math` | Rounding, trigonometry, exponential/logarithmic functions, base conversion, random numbers, and value classification — one method per native PHP math function |
| `Array` | `Extension\Array` | A growing subset of PHP's array functions; currently `sum` and `product` |
| `BcMath` | `Extension\BcMath` | Arbitrary-precision arithmetic (`add`, `sub`, `mul`, `div`, `comp`), requires `ext-bcmath` |
| `Allocation` | `Extension\Allocation` | Proportional split of an amount across shares, remainder folded into one share |

`BcMath` avoids the classic floating-point trap and computes exact decimal results:

```php
use PhpArchitecture\LazyOperators\Foundation\Extension\Allocation\Allocation;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\BcMath;

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

## Writing your own extension

The library has a consistent pattern for adding a new operator, whether it lives in
`Foundation\Extension` or your own code. Every concrete node class:

1. Declares its own `KEY` (a short string label), `UID` (a UUIDv4 string), and `VERSION` constant.
   `KEY` and `UID` must be unique across the whole set of registered nodes.
2. Implements exactly one typed contract from `Foundation\Type` — `NumberValue`, `StringValue`,
   `BooleanValue`, `ArrayValue`, `ObjectValue`, or a package-specific one such as
   `Extension\BcMath\PrecisionNumberValue`.
3. Exposes a static `formula(): string` method that documents the computation in plain text, for
   example `AdditionOperator::formula()` returns `'f(left, right) = left + right'`.
4. If it wraps a native PHP function, it adds `use GuardsNativeFunction;`. Its constructor calls
   `self::guardAvailable('function_name')`. This throws a clear exception when the underlying
   function is not loaded, instead of a generic PHP error later.
5. Ships a matching class under `Infrastructure\Persistence\Serializer`. Register it by `UID` in
   `ExpressionSerializers::default()`. This lets the new node serialize and deserialize like every
   other one (see the next section).

`Extension\Allocation` and `Extension\BcMath` follow this pattern end to end and are good
references to copy from.

## Persisting expressions

An expression tree serializes to a plain array and rebuilds from it later, provided every node in
the tree has a registered serializer:

```php
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\AdditionOperator;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializers;

$registry = ExpressionSerializers::default();

$expression = new AdditionOperator(new IntLiteral(2), new IntLiteral(3));
$serialized = $registry->serialize($expression); // uid, key, class, version, args
$hydrated   = $registry->deserialize($serialized);

$hydrated(); // 5, same as $expression()
```

`ExpressionSerializers::default()` registers a serializer for every node the library ships, keyed
by that node's `UID`. Custom closures used with `Custom::callback()` need one extra step. Register
the closure under a name first. This lets the registry look it up again after deserializing.

```php
use PhpArchitecture\LazyOperators\Foundation\Custom\CallbackOperator;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\CallbackRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializers;

$callbacks = new CallbackRegistry();
$callbacks->register('sum', static fn (int $a, int $b): int => $a + $b);

$registry = ExpressionSerializers::default($callbacks);

$expression = new CallbackOperator($callbacks->resolve('sum'), new IntLiteral(2), new IntLiteral(3));
$hydrated   = $registry->deserialize($registry->serialize($expression));

$hydrated(); // 5
```

## Testing and quality tooling

```bash
composer test           # PHPUnit
composer test:coverage  # PHPUnit with Cobertura coverage output
composer code:analyse   # PHPStan, level 8
composer code:fix:dry   # PHP-CS-Fixer, check only
composer code:fix       # PHP-CS-Fixer, apply fixes
```

Tests live under `tests/Unit` (one file per class under `src/Foundation`), `tests/Functional`
(cross-cutting scenarios, such as BcMath feeding Allocation), and `tests/Support` (test doubles
for `Expression`).

## Contributing

Run `composer test`, `composer code:analyse`, and `composer code:fix:dry` before opening a pull
request. All three run in CI on every push.

## License

MIT. See [LICENSE](LICENSE).
