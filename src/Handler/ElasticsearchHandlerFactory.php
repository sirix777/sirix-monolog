<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Elasticsearch\Client;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use ReflectionException;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class ElasticsearchHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    /**
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): HandlerInterface
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);

        return $this->newHandler(ElasticsearchHandler::class, [
            $this->serviceObject($container, $handlerDefinition->options['client'] ?? null, 'client', 'Elasticsearch', [
                Client::class,
                'Elastic\Elasticsearch\Client',
            ]),
            $configReader->array('handler_options', []),
            $configReader->enum('level', Level::class, Level::Debug),
            $configReader->bool('bubble', true),
        ]);
    }
}
