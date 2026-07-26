<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Infrastructure\Persistence;

use PhpArchitecture\LazyOperators\Foundation\Arithmetic\AdditionOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\DivisionOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\ExponentiationOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\ModuloOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\MultiplicationOperator;
use PhpArchitecture\LazyOperators\Foundation\Arithmetic\SubtractionOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparator\SpaceshipOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\EqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\GreaterThanOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\GreaterThanOrEqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\IdenticalOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\LessThanOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\LessThanOrEqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\NotEqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Comparison\NotIdenticalOperator;
use PhpArchitecture\LazyOperators\Foundation\Conditional\CaseOfSwitchCase;
use PhpArchitecture\LazyOperators\Foundation\Conditional\IfElseOperator;
use PhpArchitecture\LazyOperators\Foundation\Conditional\SwitchCaseOperator;
use PhpArchitecture\LazyOperators\Foundation\Custom\CallbackOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification\IsFiniteFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification\IsInfiniteFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification\IsNanFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\BaseConvertFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\BinDecFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\DecBinFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\DecHexFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\DecOctFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\HexDecFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\OctDecFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\ExpFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\Expm1Function;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\HypotFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\Log10Function;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\Log1pFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\LogFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\PowFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\SqrtFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\AbsFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\FdivFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\FmodFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\IntdivFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\MaxFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\MinFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\GetRandMaxFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\LcgValueFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\MtGetRandMaxFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\MtRandFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\MtSrandFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\RandFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\RandomIntFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\SrandFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding\CeilFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding\FloorFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding\RoundFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AcosFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AcoshFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AsinFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AsinhFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\Atan2Function;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AtanFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AtanhFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\CosFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\CoshFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\Deg2RadFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\PiFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\Rad2DegFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\SinFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\SinhFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\TanFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\TanhFunction;
use PhpArchitecture\LazyOperators\Foundation\Logical\AndOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\NotOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\OrOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\XorOperator;
use PhpArchitecture\LazyOperators\Foundation\Static\Value;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\CallbackRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\IncompatibleExpressionVersionException;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\UnknownExpressionUidException;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\UnpersistableCallbackException;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\UnpersistableValueException;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\UnsupportedExpressionException;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializers;
use PhpArchitecture\LazyOperators\Tests\Support\RecordingExpression;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ExpressionSerializerRegistryTest extends TestCase
{
    /**
     * @return array<string, Expression>
     */
    private function sampleExpressions(): array
    {
        return [
            'addition' => new AdditionOperator(new Value(2), new Value(3)),
            'subtraction' => new SubtractionOperator(new Value(5), new Value(2)),
            'multiplication' => new MultiplicationOperator(new Value(4), new Value(3)),
            'division' => new DivisionOperator(new Value(10), new Value(2)),
            'modulo' => new ModuloOperator(new Value(10), new Value(3)),
            'exponentiation' => new ExponentiationOperator(new Value(2), new Value(3)),
            'equal' => new EqualOperator(new Value(1), new Value(1)),
            'not_equal' => new NotEqualOperator(new Value(1), new Value(2)),
            'identical' => new IdenticalOperator(new Value(1), new Value(1)),
            'not_identical' => new NotIdenticalOperator(new Value('1'), new Value(1)),
            'greater_than' => new GreaterThanOperator(new Value(2), new Value(1)),
            'greater_than_or_equal' => new GreaterThanOrEqualOperator(new Value(2), new Value(2)),
            'less_than' => new LessThanOperator(new Value(1), new Value(2)),
            'less_than_or_equal' => new LessThanOrEqualOperator(new Value(2), new Value(2)),
            'and' => new AndOperator(new Value(true), new Value(true)),
            'or' => new OrOperator(new Value(false), new Value(true)),
            'xor' => new XorOperator(new Value(true), new Value(false)),
            'not' => new NotOperator(new Value(true)),
            'spaceship' => new SpaceshipOperator(new Value(1), new Value(2)),
            'value' => new Value(42),
            'if_else' => new IfElseOperator(new Value(true), new Value('yes'), new Value('no')),
            'switch_case' => new SwitchCaseOperator(
                new Value(2),
                [
                    new CaseOfSwitchCase(new Value(1), new Value('a')),
                    new CaseOfSwitchCase(new Value(2), new Value('b')),
                ],
                new Value('fallback'),
            ),
            'nested' => new AdditionOperator(new AdditionOperator(new Value(1), new Value(2)), new Value(3)),
            'math_ceil' => new CeilFunction(new Value(4.2)),
            'math_floor' => new FloorFunction(new Value(4.8)),
            'math_round' => new RoundFunction(new Value(3.14159), new Value(2)),
            'math_sin' => new SinFunction(new Value(1.0)),
            'math_cos' => new CosFunction(new Value(1.0)),
            'math_tan' => new TanFunction(new Value(1.0)),
            'math_asin' => new AsinFunction(new Value(0.5)),
            'math_acos' => new AcosFunction(new Value(0.5)),
            'math_atan' => new AtanFunction(new Value(1.0)),
            'math_atan2' => new Atan2Function(new Value(1.0), new Value(1.0)),
            'math_sinh' => new SinhFunction(new Value(1.0)),
            'math_cosh' => new CoshFunction(new Value(1.0)),
            'math_tanh' => new TanhFunction(new Value(1.0)),
            'math_asinh' => new AsinhFunction(new Value(1.0)),
            'math_acosh' => new AcoshFunction(new Value(2.0)),
            'math_atanh' => new AtanhFunction(new Value(0.5)),
            'math_deg2rad' => new Deg2RadFunction(new Value(180.0)),
            'math_rad2deg' => new Rad2DegFunction(new Value(M_PI)),
            'math_pi' => new PiFunction(),
            'math_exp' => new ExpFunction(new Value(1.0)),
            'math_expm1' => new Expm1Function(new Value(1.0)),
            'math_log' => new LogFunction(new Value(M_E)),
            'math_log10' => new Log10Function(new Value(100.0)),
            'math_log1p' => new Log1pFunction(new Value(1.0)),
            'math_pow' => new PowFunction(new Value(2), new Value(10)),
            'math_sqrt' => new SqrtFunction(new Value(4.0)),
            'math_hypot' => new HypotFunction(new Value(3.0), new Value(4.0)),
            'math_abs' => new AbsFunction(new Value(-5)),
            'math_fmod' => new FmodFunction(new Value(10.0), new Value(3.0)),
            'math_fdiv' => new FdivFunction(new Value(10.0), new Value(4.0)),
            'math_intdiv' => new IntdivFunction(new Value(10), new Value(3)),
            'math_max' => new MaxFunction(new Value(1), new Value(5), new Value(3)),
            'math_min' => new MinFunction(new Value(1), new Value(5), new Value(3)),
            'math_bindec' => new BinDecFunction(new Value('101')),
            'math_decbin' => new DecBinFunction(new Value(5)),
            'math_dechex' => new DecHexFunction(new Value(255)),
            'math_hexdec' => new HexDecFunction(new Value('ff')),
            'math_decoct' => new DecOctFunction(new Value(8)),
            'math_octdec' => new OctDecFunction(new Value('17')),
            'math_base_convert' => new BaseConvertFunction(new Value('ff'), new Value(16), new Value(2)),
            'math_getrandmax' => new GetRandMaxFunction(),
            'math_mt_getrandmax' => new MtGetRandMaxFunction(),
            'math_srand' => new SrandFunction(new Value(42)),
            'math_mt_srand' => new MtSrandFunction(new Value(42)),
            'math_is_finite' => new IsFiniteFunction(new Value(1.5)),
            'math_is_infinite' => new IsInfiniteFunction(new Value(1.5)),
            'math_is_nan' => new IsNanFunction(new Value(1.5)),
        ];
    }

    /**
     * rand()/mt_rand()/random_int()/lcg_value() draw from the process-global RNG, so two
     * independent invocations (original vs. hydrated) can never be asserted equal — these are
     * covered separately by testNonDeterministicMathExpressionsRoundTripToAValidValue() instead.
     *
     * @return array<string, Expression>
     */
    private function nondeterministicSampleExpressions(): array
    {
        return [
            'math_rand' => new RandFunction(new Value(1), new Value(10)),
            'math_mt_rand' => new MtRandFunction(new Value(1), new Value(10)),
            'math_random_int' => new RandomIntFunction(new Value(1), new Value(10)),
            'math_lcg_value' => new LcgValueFunction(),
        ];
    }

    public function testEveryExpressionRoundTripsToTheSameResult(): void
    {
        $registry = ExpressionSerializers::default();

        foreach ($this->sampleExpressions() as $label => $expression) {
            $hydrated = $registry->deserialize($registry->serialize($expression));

            self::assertSame($expression(), $hydrated(), "Round-trip mismatch for \"{$label}\"");
        }
    }

    public function testNonDeterministicMathExpressionsRoundTripToAValidValue(): void
    {
        $registry = ExpressionSerializers::default();

        foreach ($this->nondeterministicSampleExpressions() as $label => $expression) {
            $hydrated = $registry->deserialize($registry->serialize($expression));

            $result = $hydrated();

            self::assertSame(
                $expression::class,
                $hydrated::class,
                "Round-trip class mismatch for \"{$label}\"",
            );
            self::assertTrue(
                is_int($result) || is_float($result),
                "Round-trip result for \"{$label}\" should be numeric, got " . get_debug_type($result),
            );
        }
    }

    public function testSerializedShapeCarriesTheExpressionsOwnUidKeyClassAndVersion(): void
    {
        $registry = ExpressionSerializers::default();
        $samples = [...$this->sampleExpressions(), ...$this->nondeterministicSampleExpressions()];

        foreach ($samples as $label => $expression) {
            $class = $expression::class;
            $serialized = $registry->serialize($expression);

            self::assertSame($class::UID, $serialized['uid'], "uid mismatch for \"{$label}\"");
            self::assertSame($class::KEY, $serialized['key'], "key mismatch for \"{$label}\"");
            self::assertSame($class, $serialized['class'], "class mismatch for \"{$label}\"");
            self::assertSame($class::VERSION, $serialized['version'], "version mismatch for \"{$label}\"");
        }
    }

    public function testCallbackOperatorRoundTripsWhenItsClosureIsRegistered(): void
    {
        $callbacks = new CallbackRegistry();
        $callbacks->register('sum', static fn (int $a, int $b): int => $a + $b);
        $registry = ExpressionSerializers::default($callbacks);

        $expression = new CallbackOperator($callbacks->resolve('sum'), new Value(2), new Value(3));

        $hydrated = $registry->deserialize($registry->serialize($expression));

        self::assertSame(5, $hydrated());
    }

    public function testCallbackOperatorSerializationThrowsWhenClosureIsNotRegistered(): void
    {
        $expression = new CallbackOperator(static fn (): int => 1);

        $this->expectException(UnpersistableCallbackException::class);
        $this->expectExceptionMessage(
            'This closure was never registered via CallbackRegistry::register() and cannot be persisted.',
        );

        ExpressionSerializers::default()->serialize($expression);
    }

    public function testValueSerializationThrowsForNonJsonSafePayload(): void
    {
        $this->expectException(UnpersistableValueException::class);
        $this->expectExceptionMessage('Value wraps a "stdClass", which is not JSON-safe and cannot be persisted.');

        ExpressionSerializers::default()->serialize(new Value(new stdClass()));
    }

    public function testDeserializeThrowsForUnknownUid(): void
    {
        $this->expectException(UnknownExpressionUidException::class);
        $this->expectExceptionMessage('No serializer registered for Expression uid "not-a-registered-uid".');

        ExpressionSerializers::default()->deserialize([
            'uid' => 'not-a-registered-uid',
            'key' => 'value',
            'class' => Value::class,
            'version' => '1.0',
            'args' => [1],
        ]);
    }

    public function testDeserializeThrowsOnVersionMismatch(): void
    {
        $registry = ExpressionSerializers::default();
        $serialized = $registry->serialize(new Value(1));
        $serialized['version'] = '999.0';

        $this->expectException(IncompatibleExpressionVersionException::class);
        $this->expectExceptionMessage(sprintf(
            'Stored version "999.0" for Expression uid "%s" is incompatible with the currently registered version "%s".',
            Value::UID,
            Value::VERSION,
        ));

        $registry->deserialize($serialized);
    }

    public function testSerializeThrowsForAnUnregisteredExpressionClass(): void
    {
        $expression = new class implements Expression {
            public function __invoke(): mixed
            {
                return 'unregistered';
            }
        };

        $this->expectException(UnsupportedExpressionException::class);
        $this->expectExceptionMessage(sprintf('No serializer registered for Expression class "%s".', $expression::class));

        ExpressionSerializers::default()->serialize($expression);
    }

    public function testSerializedShapeIsIdenticalRegardlessOfDecoration(): void
    {
        $registry = ExpressionSerializers::default();

        $plain = new AdditionOperator(new Value(2), new Value(3));
        $decorated = new RecordingExpression(new AdditionOperator(new Value(2), new Value(3)));

        self::assertSame($registry->serialize($plain), $registry->serialize($decorated));
    }
}
