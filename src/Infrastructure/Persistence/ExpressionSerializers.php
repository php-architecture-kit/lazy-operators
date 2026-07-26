<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence;

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
use PhpArchitecture\LazyOperators\Foundation\Static\Value;
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
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Static\ValueSerializer;

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
            Value::UID => new ValueSerializer(),
        ], $callbacks ?? new CallbackRegistry());
    }
}
