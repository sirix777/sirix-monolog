<?php

declare(strict_types=1);

namespace Sirix\Monolog\Exception;

use OutOfBoundsException;
use Psr\Container\NotFoundExceptionInterface;

class UnknownServiceException extends OutOfBoundsException implements NotFoundExceptionInterface {}
