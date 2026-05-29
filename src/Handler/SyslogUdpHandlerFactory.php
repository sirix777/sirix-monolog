<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use const LOG_USER;

use Monolog\Handler\SyslogUdpHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

use function in_array;
use function is_int;
use function is_string;

class SyslogUdpHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): SyslogUdpHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return new SyslogUdpHandler(
            $options->requiredNonEmptyString('host'),
            $options->int('port', 514),
            $this->facility($definition->options['facility'] ?? LOG_USER),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
            $options->string('ident', 'php'),
            $this->rfc($options->int('rfc', SyslogUdpHandler::RFC5424)),
        );
    }

    private function facility(mixed $facility): int|string
    {
        if (is_int($facility) || is_string($facility)) {
            return $facility;
        }

        throw new InvalidConfigException('Syslog UDP handler option "facility" must be an int or string.');
    }

    /**
     * @return SyslogUdpHandler::RFC*
     */
    private function rfc(int $rfc): int
    {
        if (! in_array($rfc, [SyslogUdpHandler::RFC3164, SyslogUdpHandler::RFC5424, SyslogUdpHandler::RFC5424e], true)) {
            throw new InvalidConfigException('Syslog UDP handler option "rfc" must be a valid SyslogUdpHandler RFC constant.');
        }

        return $rfc;
    }
}
