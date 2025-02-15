<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class BrowserConsoleHandlerFactory implements FactoryInterface
{
    public function __invoke(array $options): BrowserConsoleHandler
    {
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new BrowserConsoleHandler(
            $level,
            $bubble
        );
    }
}
