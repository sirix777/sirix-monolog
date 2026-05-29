<?php

declare(strict_types=1);

namespace Sirix\Monolog\Factory;

use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Sirix\ContainerResolver\ContainerResolver;
use Sirix\Monolog\Config\MonologConfig;
use Sirix\Monolog\Registry\ChannelRegistry;

use function is_string;
use function trim;

final class LoggerFactory
{
    public function __invoke(
        ContainerInterface $container,
        string $requestedName = LoggerInterface::class,
        ?array $options = null,
    ): LoggerInterface {
        $resolver = ContainerResolver::forFactory($container, self::class);
        $config = $resolver->get(MonologConfig::class);
        $channelOption = $options['channel'] ?? null;
        $channelId = is_string($channelOption) && '' !== trim($channelOption)
            ? trim($channelOption)
            : null;

        $registry = $resolver->get(ChannelRegistry::class);

        if (null !== $channelId) {
            return $registry->get($channelId);
        }

        $loggerService = $config->loggerService($requestedName);
        $logger = $registry->get($loggerService->channel);

        if (null !== $loggerService->name && $logger instanceof Logger) {
            return $logger->withName($loggerService->name);
        }

        return $logger;
    }
}
