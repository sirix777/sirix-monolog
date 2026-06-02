<?php

declare(strict_types=1);

namespace Sirix\Monolog\Handler;

use Monolog\Handler\SamplingHandler;
use Psr\Container\ContainerInterface;
use Sirix\ContainerResolver\ConfigReader;
use Sirix\Monolog\Config\HandlerDefinition;
use Sirix\Monolog\Exception\InvalidConfigException;

class SamplingHandlerFactory implements HandlerFactoryInterface, HandlerRegistryAwareInterface
{
    use HandlerRegistryTrait;

    public function create(ContainerInterface $container, HandlerDefinition $handlerDefinition): SamplingHandler
    {
        $configReader = ConfigReader::fromArray($handlerDefinition->options, self::class);
        $factor = $configReader->requiredInt('factor');

        if ($factor < 1) {
            throw new InvalidConfigException('Factor is missing or is less then 1');
        }

        return new SamplingHandler(
            $this->getHandlerRegistry()->get($configReader->requiredNonEmptyString('handler')),
            $factor,
        );
    }
}
