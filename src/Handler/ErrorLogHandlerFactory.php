<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

use function in_array;

class ErrorLogHandlerFactory implements FactoryInterface
{
    public function __invoke(array $options): ErrorLogHandler
    {
        $rawMessageType = (int) ($options['messageType'] ?? ErrorLogHandler::OPERATING_SYSTEM);

        // Only 0 (OPERATING_SYSTEM) or 4 (SAPI) are supported by Monolog\Handler\ErrorLogHandler
        /** @var 0|4 $messageType */
        $messageType = in_array($rawMessageType, [ErrorLogHandler::OPERATING_SYSTEM, ErrorLogHandler::SAPI], true)
            ? $rawMessageType
            : ErrorLogHandler::OPERATING_SYSTEM;

        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);
        $expandNewlines = (bool) ($options['expandNewlines'] ?? false);

        return new ErrorLogHandler($messageType, $level, $bubble, $expandNewlines);
    }
}
