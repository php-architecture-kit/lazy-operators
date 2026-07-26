<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Conditional\CaseOfSwitchCase;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class CaseOfSwitchCaseTest extends TestCase
{
    public function testStoresConditionAndValueAsGiven(): void
    {
        $condition = new SpyExpression(true);
        $value = new SpyExpression('value');

        $case = new CaseOfSwitchCase($condition, $value);

        self::assertSame($condition, $case->condition);
        self::assertSame($value, $case->value);
    }
}
