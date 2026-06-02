<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\PHPConsoleHandler;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use ReflectionException;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class PHPConsoleHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): HandlerInterface
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return $this->newHandler(PHPConsoleHandler::class, [
            $configReader->array('php_console_options', []),
            $this->optionalServiceObject($container, $handlerDefinition->options['connector'] ?? null, 'connector', 'PHP Console', 'PhpConsole\Connector'),
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
        ]);
    }
}
