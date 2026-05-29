<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use const LOG_PID;
use const LOG_USER;

use Monolog\Handler\SyslogHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

use function is_int;
use function is_string;

class SyslogHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $definition): SyslogHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $level = $options->enum('level', Level::class, Level::Debug);

        return new SyslogHandler(
            $options->requiredNonEmptyString('ident'),
            $this->facility($definition->options['facility'] ?? LOG_USER),
            $level,
            $options->bool('bubble', true),
            $options->int('log_opts', LOG_PID),
        );
    }

    private function facility(mixed $facility): int|string
    {
        if (is_int($facility) || is_string($facility)) {
            return $facility;
        }

        throw new InvalidConfigException('Syslog handler option "facility" must be an int or string.');
    }
}
