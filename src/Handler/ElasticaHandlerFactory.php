<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Elastica\Client;
use Monolog\Handler\ElasticaHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use ReflectionException;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class ElasticaHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): HandlerInterface
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return $this->newHandler(ElasticaHandler::class, [
            $this->serviceObject($container, $handlerDefinition->options['client'] ?? null, 'client', 'Elastica', [Client::class]),
            $configReader->array('handler_options', []),
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
        ]);
    }
}
