<?php

declare(strict_types=1);

namespace Sirix\Monolog\Factory;

use Monolog\Logger;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\MonologConfig;
use Sirix\Monolog\Registry\ChannelRegistry;

use function is_string;
use function trim;

final class LoggerFactory
{
    /**
     * @throws ContainerExceptionInterface
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName = LoggerInterface::class,
        ?array $options = null
    ): LoggerInterface {
        $containerResolver = ContainerResolver::forFactory($container, self::class);
        $monologConfig = $containerResolver->get(MonologConfig::class);
        $channelOption = $options['channel'] ?? null;
        $channelId = is_string($channelOption) && '' !== trim($channelOption)
            ? trim($channelOption)
            : null;

        $channelRegistry = $containerResolver->get(ChannelRegistry::class);

        if (null !== $channelId) {
            return $channelRegistry->get($channelId);
        }

        $loggerService = $monologConfig->loggerService($requestedName);
        $logger = $channelRegistry->get($loggerService->channel);

        if (null !== $loggerService->name && $logger instanceof Logger) {
            return $logger->withName($loggerService->name);
        }

        return $logger;
    }
}
