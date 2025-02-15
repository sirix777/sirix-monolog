<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use const LOG_USER;

use Monolog\Handler\MissingExtensionException;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Level;
use Sirix\Monolog\FactoryInterface;

class SyslogUdpHandlerFactory implements FactoryInterface
{
    /**
     * @throws MissingExtensionException
     */
    public function __invoke(array $options): SyslogUdpHandler
    {
        $host = (string) ($options['host'] ?? '');
        $port = (int) ($options['host'] ?? 514);
        $facility = (int) ($options['facility'] ?? LOG_USER);
        $level = $options['level'] ?? Level::Debug;
        $bubble = (bool) ($options['bubble'] ?? true);
        $ident = (string) ($options['ident'] ?? 'php');

        return new SyslogUdpHandler(
            $host,
            $port,
            $facility,
            $level,
            $bubble,
            $ident
        );
    }
}
