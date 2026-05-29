<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Elastica\Client;
use Monolog\Handler\ElasticaHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;

class ElasticaHandlerFactory implements HandlerFactoryInterface
{
    use ReflectiveHandlerFactoryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $definition): HandlerInterface
    {
        $options = ConfigReader::fromArray($definition->options, self::class);

        return $this->newHandler(ElasticaHandler::class, [
            $this->serviceObject($container, $definition->options['client'] ?? null, 'client', 'Elastica', [Client::class]),
            $options->array('handler_options', []),
            $options->enum('level', Level::class, Level::Debug),
            $options->bool('bubble', true),
        ]);
    }
}
