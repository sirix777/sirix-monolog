<?php

declare(strict_types=1);

namespace Sirix\Monolog\Builder;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Sirix\Monolog\Config\MonologConfig;
use Sirix\Monolog\Registry\HandlerRegistry;
use Sirix\Monolog\Registry\ProcessorRegistry;

use function array_reverse;

final readonly class LoggerBuilder
{
    public function __construct(
        private MonologConfig $monologConfig,
        private HandlerRegistry $handlerRegistry,
        private ProcessorRegistry $processorRegistry,
    ) {}

    public function build(string $channelId): LoggerInterface
    {
        $channelDefinition = $this->monologConfig->channel($channelId);
        $logger = new Logger($channelDefinition->name);

        foreach (array_reverse($channelDefinition->handlers) as $handlerId) {
            $logger->pushHandler($this->handlerRegistry->get($handlerId));
        }

        foreach (array_reverse($channelDefinition->processors) as $processorId) {
            $logger->pushProcessor($this->processorRegistry->get($processorId));
        }

        return $logger;
    }
}
