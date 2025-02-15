<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\NullHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class NullHandlerFactory implements FactoryInterface
{
    public function __invoke(array $options): NullHandler
    {
        $level = $options['level'] ?? Level::Debug;

        return new NullHandler($level);
    }
}
