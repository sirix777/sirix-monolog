<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\LogmaticHandler;
use Monolog\Handler\MissingExtensionException;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

// phpcs:disable WebimpressCodingStandard.NamingConventions.ValidVariableName
class LogmaticHandlerFactory implements FactoryInterface
{
    /**
     * @throws MissingExtensionException
     */
    public function __invoke(array $options): LogmaticHandler
    {
        $token = (string) ($options['token'] ?? '');
        $hostname = (string) ($options['hostname'] ?? '');
        $appname = (string) ($options['appname'] ?? '');
        $useSSL = (bool) ($options['useSSL'] ?? true);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new LogmaticHandler(
            $token,
            $hostname,
            $appname,
            $useSSL,
            $level,
            $bubble
        );
    }
}
