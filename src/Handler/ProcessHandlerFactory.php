<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\ProcessHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class ProcessHandlerFactory implements FactoryInterface
{
    public function __invoke(array $options): ProcessHandler
    {
        $command = (string) ($options['command'] ?? null);
        $cwd = (string) ($options['cwd'] ?? null);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new ProcessHandler(
            $command,
            $level,
            $bubble,
            $cwd
        );
    }
}
