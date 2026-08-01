<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Exception;

use LogicException;
use PhpArchitecture\LazyOperators\Foundation\Runtime\Port;

final class PortNotBoundException extends LogicException implements LazyOperatorsException
{
    public static function create(Port $port): self
    {
        return new self(sprintf(
            'Port#%d has no bound Expression yet: call setExpr() before invoking it.',
            spl_object_id($port),
        ));
    }
}
