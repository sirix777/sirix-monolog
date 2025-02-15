<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\NoopHandler;
use Sirix\Monolog\FactoryInterface;

class NoopHandlerFactory implements FactoryInterface
{
    public function __invoke(array $options): NoopHandler
    {
        return new NoopHandler();
    }
}
