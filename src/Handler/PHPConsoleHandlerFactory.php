<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\PHPConsoleHandler;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class PHPConsoleHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): HandlerInterface
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return $this->newHandler(PHPConsoleHandler::class, [
            $options->array('php_console_options', []),
            $this->optionalServiceObject($container, $definition->options['connector'] ?? null, 'connector', 'PHP Console', 'PhpConsole\Connector'),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
        ]);
    }
}
