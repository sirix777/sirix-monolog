<?php

declare(strict_types=1);

namespace Sirix\Monolog\Registry;

use Psr\Log\LoggerInterface;
use Sirix\Monolog\Builder\LoggerBuilder;

final class ChannelRegistry
{
    /** @var array<string, LoggerInterface> */
    private array $loggers = [];

    public function __construct(private readonly LoggerBuilder $loggerBuilder) {}

    public function get(string $channelId): LoggerInterface
    {
        return $this->loggers[$channelId] ??= $this->loggerBuilder->build($channelId);
    }
}
