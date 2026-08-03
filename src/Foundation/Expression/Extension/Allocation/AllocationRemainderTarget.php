<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Allocation;

enum AllocationRemainderTarget
{
    case First;
    case Largest;
    case Smallest;
    case Last;
}
