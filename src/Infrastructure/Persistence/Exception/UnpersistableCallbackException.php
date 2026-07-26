<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception;

use RuntimeException;

final class UnpersistableCallbackException extends RuntimeException implements LazyOperatorsPersistenceException {}
