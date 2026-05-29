<?php

declare(strict_types=1);

namespace Sirix\Monolog\Builder;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Sirix\Monolog\Config\MonologConfig;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Monolog\Registry\ProcessorRegistry;

final readonly class LoggerBuilder
{
    public function __construct(
        private MonologConfig $config,
        private HandlerRegistry $handlers,
        private ProcessorRegistry $processors,
    ) {}

    public function build(string $channelId): LoggerInterface
    {
        $definition = $this->config->channel($channelId);
        $logger = new Logger($definition->name);

        foreach ($definition->handlers as $handlerId) {
            $logger->pushHandler($this->handlers->get($handlerId));
        }

        foreach ($definition->processors as $processorId) {
            $logger->pushProcessor($this->processors->get($processorId));
        }

        return $logger;
    }
}
