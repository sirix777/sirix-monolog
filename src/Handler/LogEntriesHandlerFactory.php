<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\MissingExtensionException;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

// phpcs:disable WebimpressCodingStandard.NamingConventions.ValidVariableName
class LogEntriesHandlerFactory implements FactoryInterface
{
    /**
     * @throws MissingExtensionException
     */
    public function __invoke(array $options): LogEntriesHandler
    {
        $token = (string) ($options['token'] ?? '');
        $useSSL = (bool) ($options['useSSL'] ?? true);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);

        return new LogEntriesHandler(
            $token,
            $useSSL,
            $level,
            $bubble
        );
    }
}
