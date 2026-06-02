<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\ProcessHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

use function is_float;
use function is_int;

class ProcessHandlerFactory implements HandlerFactoryInterface
{
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): ProcessHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);
        $level = $configReader->enum('level', Level::class, Level::Debug);

        return new ProcessHandler(
            $configReader->requiredNonEmptyString('command'),
            $level,
            $configReader->bool('bubble', true),
            $configReader->optionalNonEmptyString('cwd'),
            $this->timeout($handlerDefinition->options['timeout'] ?? 1.0),
        );
    }

    private function timeout(mixed $timeout): float
    {
        if (is_float($timeout) || is_int($timeout)) {
            return (float) $timeout;
        }

        throw new InvalidConfigException('Process handler option "timeout" must be a float or int.');
    }
}
