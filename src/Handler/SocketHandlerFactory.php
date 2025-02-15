<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\SocketHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

use function ini_get;

class SocketHandlerFactory implements FactoryInterface
{
    public function __invoke(array $options): SocketHandler
    {
        $connectionString = (string) ($options['connectionString'] ?? '');
        $timeout = (float) ($options['timeout'] ?? ini_get('default_socket_timeout'));
        $writeTimeout = (int) ($options['writeTimeout'] ?? ini_get('default_socket_timeout'));
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        $handler = new SocketHandler(
            $connectionString,
            $level,
            $bubble
        );

        if (! empty($timeout)) {
            $handler->setConnectionTimeout($timeout);
        }

        if (0 !== $writeTimeout) {
            $handler->setTimeout($writeTimeout);
            $handler->setWritingTimeout($writeTimeout);
        }

        return $handler;
    }
}
