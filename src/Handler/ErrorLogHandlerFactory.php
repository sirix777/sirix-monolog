<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class ErrorLogHandlerFactory implements FactoryInterface
{
    public function __invoke(array $options): ErrorLogHandler
    {
        /** @var 0|1|3|4 $messageType */
        $messageType = (int) ($options['messageType'] ?? ErrorLogHandler::OPERATING_SYSTEM);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);
        $expandNewlines = (bool) ($options['expandNewlines'] ?? false);

        return new ErrorLogHandler($messageType, $level, $bubble, $expandNewlines);
    }
}
