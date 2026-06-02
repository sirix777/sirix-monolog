<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

class ErrorLogHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): ErrorLogHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);
        $level = $configReader->enum('level', Level::class, Level::Debug);

        return new ErrorLogHandler(
            $this->messageType($configReader->int('message_type', ErrorLogHandler::OPERATING_SYSTEM)),
            $level,
            $configReader->bool('bubble', true),
            $configReader->bool('expand_newlines', false),
        );
    }

    /**
     * @return 0|4
     */
    private function messageType(int $messageType): int
    {
        return match ($messageType) {
            ErrorLogHandler::OPERATING_SYSTEM => ErrorLogHandler::OPERATING_SYSTEM,
            ErrorLogHandler::SAPI => ErrorLogHandler::SAPI,
            default => throw new InvalidConfigException('Error log handler option "message_type" is not supported.'),
        };
    }
}
