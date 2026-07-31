<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence;

use PhpArchitecture\LazyOperators\Foundation\Allocation\AllocationFunction;
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
use PhpArchitecture\LazyOperators\Foundation\Conditional\IfElseOperator;
use PhpArchitecture\LazyOperators\Foundation\Conditional\SwitchCaseOperator;
use PhpArchitecture\LazyOperators\Foundation\Custom\CallbackOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\AndOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\NotOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\OrOperator;
use PhpArchitecture\LazyOperators\Foundation\Logical\XorOperator;
use PhpArchitecture\LazyOperators\Foundation\Static\ArrayLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\BoolLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\FloatLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\StringLiteral;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding\CeilFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding\FloorFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding\RoundFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\SinFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\CosFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\TanFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AsinFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AcosFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AtanFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\Atan2Function;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\SinhFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\CoshFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\TanhFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AsinhFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AcoshFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AtanhFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\Deg2RadFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\Rad2DegFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\PiFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\ExpFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\Expm1Function;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\LogFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\Log10Function;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\Log1pFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\PowFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\SqrtFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\HypotFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\AbsFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\FmodFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\FdivFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\IntdivFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\MaxFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\MinFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\BinDecFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\DecBinFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\DecHexFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\HexDecFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\DecOctFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\OctDecFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\BaseConvertFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\RandFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\MtRandFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\RandomIntFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\GetRandMaxFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\MtGetRandMaxFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\SrandFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\MtSrandFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\LcgValueFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Array\Aggregate\ProductFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Array\Aggregate\SumFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification\IsFiniteFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification\IsInfiniteFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Classification\IsNanFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Allocation\AllocationFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Arithmetic\AdditionOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Arithmetic\DivisionOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Arithmetic\ExponentiationOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Arithmetic\ModuloOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Arithmetic\MultiplicationOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Arithmetic\SubtractionOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Comparator\SpaceshipOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Comparison\EqualOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Comparison\GreaterThanOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Comparison\GreaterThanOrEqualOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Comparison\IdenticalOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Comparison\LessThanOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Comparison\LessThanOrEqualOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Comparison\NotEqualOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Comparison\NotIdenticalOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Conditional\IfElseOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Conditional\SwitchCaseOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Custom\CallbackOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Logical\AndOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Logical\NotOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Logical\OrOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Logical\XorOperatorSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Static\ArrayLiteralSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Static\BoolLiteralSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Static\FloatLiteralSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Static\IntLiteralSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Static\StringLiteralSerializer;

use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Rounding\CeilFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Rounding\FloorFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Rounding\RoundFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\SinFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\CosFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\TanFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\AsinFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\AcosFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\AtanFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\Atan2FunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\SinhFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\CoshFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\TanhFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\AsinhFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\AcoshFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\AtanhFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\Deg2RadFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\Rad2DegFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Trigonometry\PiFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Exponential\ExpFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Exponential\Expm1FunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Exponential\LogFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Exponential\Log10FunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Exponential\Log1pFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Exponential\PowFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Exponential\SqrtFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Exponential\HypotFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Numeric\AbsFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Numeric\FmodFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Numeric\FdivFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Numeric\IntdivFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Numeric\MaxFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Numeric\MinFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Conversion\BinDecFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Conversion\DecBinFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Conversion\DecHexFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Conversion\HexDecFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Conversion\DecOctFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Conversion\OctDecFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Conversion\BaseConvertFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Random\RandFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Random\MtRandFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Random\RandomIntFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Random\GetRandMaxFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Random\MtGetRandMaxFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Random\SrandFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Random\MtSrandFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Random\LcgValueFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Classification\IsFiniteFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Classification\IsInfiniteFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Classification\IsNanFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Array\Aggregate\ProductFunctionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Array\Aggregate\SumFunctionSerializer;

final class ExpressionSerializers
{
    public static function default(?CallbackRegistry $callbacks = null): ExpressionSerializerRegistry
    {
        return new ExpressionSerializerRegistry([
            AdditionOperator::UID => new AdditionOperatorSerializer(),
            SubtractionOperator::UID => new SubtractionOperatorSerializer(),
            MultiplicationOperator::UID => new MultiplicationOperatorSerializer(),
            DivisionOperator::UID => new DivisionOperatorSerializer(),
            ModuloOperator::UID => new ModuloOperatorSerializer(),
            ExponentiationOperator::UID => new ExponentiationOperatorSerializer(),
            EqualOperator::UID => new EqualOperatorSerializer(),
            NotEqualOperator::UID => new NotEqualOperatorSerializer(),
            IdenticalOperator::UID => new IdenticalOperatorSerializer(),
            NotIdenticalOperator::UID => new NotIdenticalOperatorSerializer(),
            GreaterThanOperator::UID => new GreaterThanOperatorSerializer(),
            GreaterThanOrEqualOperator::UID => new GreaterThanOrEqualOperatorSerializer(),
            LessThanOperator::UID => new LessThanOperatorSerializer(),
            LessThanOrEqualOperator::UID => new LessThanOrEqualOperatorSerializer(),
            AndOperator::UID => new AndOperatorSerializer(),
            OrOperator::UID => new OrOperatorSerializer(),
            XorOperator::UID => new XorOperatorSerializer(),
            NotOperator::UID => new NotOperatorSerializer(),
            SpaceshipOperator::UID => new SpaceshipOperatorSerializer(),
            IfElseOperator::UID => new IfElseOperatorSerializer(),
            SwitchCaseOperator::UID => new SwitchCaseOperatorSerializer(),
            CallbackOperator::UID => new CallbackOperatorSerializer(),
            IntLiteral::UID => new IntLiteralSerializer(),
            FloatLiteral::UID => new FloatLiteralSerializer(),
            BoolLiteral::UID => new BoolLiteralSerializer(),
            StringLiteral::UID => new StringLiteralSerializer(),
            ArrayLiteral::UID => new ArrayLiteralSerializer(),
            CeilFunction::UID => new CeilFunctionSerializer(),
            FloorFunction::UID => new FloorFunctionSerializer(),
            RoundFunction::UID => new RoundFunctionSerializer(),
            SinFunction::UID => new SinFunctionSerializer(),
            CosFunction::UID => new CosFunctionSerializer(),
            TanFunction::UID => new TanFunctionSerializer(),
            AsinFunction::UID => new AsinFunctionSerializer(),
            AcosFunction::UID => new AcosFunctionSerializer(),
            AtanFunction::UID => new AtanFunctionSerializer(),
            Atan2Function::UID => new Atan2FunctionSerializer(),
            SinhFunction::UID => new SinhFunctionSerializer(),
            CoshFunction::UID => new CoshFunctionSerializer(),
            TanhFunction::UID => new TanhFunctionSerializer(),
            AsinhFunction::UID => new AsinhFunctionSerializer(),
            AcoshFunction::UID => new AcoshFunctionSerializer(),
            AtanhFunction::UID => new AtanhFunctionSerializer(),
            Deg2RadFunction::UID => new Deg2RadFunctionSerializer(),
            Rad2DegFunction::UID => new Rad2DegFunctionSerializer(),
            PiFunction::UID => new PiFunctionSerializer(),
            ExpFunction::UID => new ExpFunctionSerializer(),
            Expm1Function::UID => new Expm1FunctionSerializer(),
            LogFunction::UID => new LogFunctionSerializer(),
            Log10Function::UID => new Log10FunctionSerializer(),
            Log1pFunction::UID => new Log1pFunctionSerializer(),
            PowFunction::UID => new PowFunctionSerializer(),
            SqrtFunction::UID => new SqrtFunctionSerializer(),
            HypotFunction::UID => new HypotFunctionSerializer(),
            AbsFunction::UID => new AbsFunctionSerializer(),
            FmodFunction::UID => new FmodFunctionSerializer(),
            FdivFunction::UID => new FdivFunctionSerializer(),
            IntdivFunction::UID => new IntdivFunctionSerializer(),
            MaxFunction::UID => new MaxFunctionSerializer(),
            MinFunction::UID => new MinFunctionSerializer(),
            BinDecFunction::UID => new BinDecFunctionSerializer(),
            DecBinFunction::UID => new DecBinFunctionSerializer(),
            DecHexFunction::UID => new DecHexFunctionSerializer(),
            HexDecFunction::UID => new HexDecFunctionSerializer(),
            DecOctFunction::UID => new DecOctFunctionSerializer(),
            OctDecFunction::UID => new OctDecFunctionSerializer(),
            BaseConvertFunction::UID => new BaseConvertFunctionSerializer(),
            RandFunction::UID => new RandFunctionSerializer(),
            MtRandFunction::UID => new MtRandFunctionSerializer(),
            RandomIntFunction::UID => new RandomIntFunctionSerializer(),
            GetRandMaxFunction::UID => new GetRandMaxFunctionSerializer(),
            MtGetRandMaxFunction::UID => new MtGetRandMaxFunctionSerializer(),
            SrandFunction::UID => new SrandFunctionSerializer(),
            MtSrandFunction::UID => new MtSrandFunctionSerializer(),
            LcgValueFunction::UID => new LcgValueFunctionSerializer(),
            IsFiniteFunction::UID => new IsFiniteFunctionSerializer(),
            IsInfiniteFunction::UID => new IsInfiniteFunctionSerializer(),
            IsNanFunction::UID => new IsNanFunctionSerializer(),
            SumFunction::UID => new SumFunctionSerializer(),
            ProductFunction::UID => new ProductFunctionSerializer(),
            AllocationFunction::UID => new AllocationFunctionSerializer(),
        ], $callbacks ?? new CallbackRegistry());
    }
}
