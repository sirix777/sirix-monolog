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
    public function create(ContainerInterface $container, HandlerDefinition $definition): ProcessHandler
    {
        $options = ConfigReader::fromArray($definition->options, self::class);
        $level = $options->enum('level', Level::class, Level::Debug);

        return new ProcessHandler(
            $options->requiredNonEmptyString('command'),
            $level,
            $options->bool('bubble', true),
            $options->optionalNonEmptyString('cwd'),
            $this->timeout($definition->options['timeout'] ?? 1.0),
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
